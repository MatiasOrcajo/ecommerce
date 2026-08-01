<?php

namespace App\Services;

use App\Models\Visit;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VisitorsStatisticsService
{
    /**
     * Mapping of filter methods to their corresponding keys.
     */
    private const FILTER_METHOD_MAP = [
        'today' => 'filterVisitorsToday',
        'seven-days' => 'filterVisitorsSevenDays',
        'this-month' => 'filterVisitorsThisMonth',
        'this-year' => 'filterVisitorsThisYear',
        'year-on-year' => 'filterVisitorsYearOnYear',
    ];

    /**
     * Retrieves filtered sales data based on the specified filter criteria.
     *
     * This method checks the provided filter parameter and determines the appropriate method to execute
     * from a predefined mapping. If the filter exists in the mapping, the corresponding method is called
     * to process and return the filtered sales data. Otherwise, it responds with an error for an invalid filter.
     *
     * @param  Request  $request  The incoming HTTP request containing the filter parameter.
     * @return mixed The filtered sales data returned by the corresponding method or a JSON error response
     *               with a 400 status code for invalid filters.
     */
    public function getFilteredVisitors(Request $request): mixed
    {
        $filter = $request->input('filter');

        if (isset(self::FILTER_METHOD_MAP[$filter])) {
            $method = self::FILTER_METHOD_MAP[$filter];

            return $this->$method();
        }

        return response()->json(['error' => 'Invalid filter'], 400); // Handle invalid filter case
    }

    private function isBot(string $userAgent, string $ip): bool
    {
        // Normalización
        $uaRaw = $userAgent ?? '';
        $ua = mb_strtolower(trim($uaRaw));

        // 0) CrawlerDetect (cobertura amplia de crawlers conocidos)
        $crawlerDetect = new \Jaybizzle\CrawlerDetect\CrawlerDetect;
        if ($crawlerDetect->isCrawler($uaRaw)) {
            return true;
        }

        // 1) Patrones explícitos (de tu lista + ajustes)
        //    Incluye librerías HTTP, headless, crawlers SEO, previewers sociales, uptime y escáneres.
        $explicitBots = [
            // Seguridad / escáneres (visto en tus datos)
            'libredtail-http', 'hello from palo alto networks', 'paloaltonetworks',
            'cortex-xpanse', 'scanning-activity', 'l9tcpid',

            // Librerías / clientes de scraping
            'curl/', 'python-requests', 'wget/', 'go-http-client', 'okhttp',
            'java/', 'node-fetch', 'libwww-perl', 'httpclient', 'aiohttp',

            // Social preview / uptime
            'facebookexternalhit', 'slackbot', 'telegrambot', 'discordbot',
            'whatsapp', 'linkedinbot', 'twitterbot', 'bitlybot', 'uptime-kuma', 'uptimerobot',

            // Crawlers SEO
            'googlebot', 'bingbot', 'yandexbot', 'duckduckbot', 'ahrefsbot', 'semrush', 'mj12bot', 'linkdexbot',

            // Headless / automatización / auditorías
            'headlesschrome', 'puppeteer', 'playwright', 'phantomjs',
            'selenium', 'lighthouse', 'pagespeed', 'rendertron',

            // Catch-all genéricos
            'crawler', 'spider', 'scraper', 'scanner', 'fetcher', 'preview',
        ];
        foreach ($explicitBots as $needle) {
            if ($needle !== '' && str_contains($ua, $needle)) {
                return true;
            }
        }

        // 2) User-Agents genéricos o vacíos (muy comunes en scrapers)
        if ($ua === '' || $ua === 'mozilla/5.0') {
            // Si además coincide con prefijos /24 sospechosos detectados en tus datos, marcamos sin dudar.
            if (self::ipStartsWithAny($ip, self::suspiciousIpPrefixes())) {
                return true;
            }

            // En general, UA vacío/genérico lo consideramos bot:
            return true;
        }

        // 3) Typos / combinaciones defectuosas típicas de bots mal formados
        $typos = ['mozlila', 'bulid', 'moblie', 'live gecko', 'winndows', 'safri'];
        foreach ($typos as $needle) {
            if (str_contains($ua, $needle)) {
                return true;
            }
        }

        // 4) Prefijos IP sospechosos (NO incluye Cloudflare)
        //    Estos /24 salieron de TU CSV con ≥90% de UA “bot-like” y >=5 hits.
        if (self::ipStartsWithAny($ip, self::suspiciousIpPrefixes())) {
            return true;
        }

        // 5) UAs extremadamente cortos suelen ser bots
        if (mb_strlen($uaRaw) <= 15) {
            return true;
        }

        // Si no disparó ninguna regla, lo tratamos como humano
        return false;
    }

    /**
     * Prefijos /24 sospechosos encontrados en tu CSV (excluyendo Cloudflare):
     *  - 205.210.31.  (Palo Alto / Cortex Xpanse)
     *  - 198.235.24.  (Palo Alto / Cortex Xpanse)
     *  - 147.185.132. (sospechoso por ratio bot-like)
     *  - 44.220.185.  (AWS, alto ratio bot-like)
     *  - 44.220.188.  (AWS, alto ratio bot-like)
     *
     * Si detectás nuevos /24 con alto ratio bot-like, agregalos acá.
     */
    private static function suspiciousIpPrefixes(): array
    {
        return [
            '205.210.31.',
            '198.235.24.',
            '147.185.132.',
            '44.220.185.',
            '44.220.188.',
        ];
    }

    private static function ipStartsWithAny(string $ip, array $prefixes): bool
    {
        foreach ($prefixes as $p) {
            if ($p !== '' && str_starts_with($ip, $p)) {
                return true;
            }
        }

        return false;
    }

    private function isMetaIp(string $ip): bool
    {

        return str_starts_with(strtolower($ip), '2a03:2880:');
    }

    private function isUs(string $ip): bool
    {
        $ip = trim($ip);

        if ($ip === '190.17.19.232') {
            return true;
        }

        // Prefijo "visual" IPv6 (limitado por representaciones alternativas)
        return str_starts_with(strtolower($ip), '2800:2130:5240:');
    }

    private function filterRealVisits($allVisits)
    {

        return $allVisits
            ->reject(function ($v) {
                return $this->isBot((string) $v->user_agent, (string) $v->ip_address);
            })
            ->reject(function ($v) {
                return $this->isMetaIp((string) $v->ip_address);
            })
            ->reject(function ($v) {
                return $this->isUs((string) $v->ip_address);
            });
    }

    /**
     * Filters and retrieves visitor data for the current day.
     *
     * This method fetches the visitors created within the current day's*/
    private function filterVisitorsToday(): string
    {
        $dates = collect([Carbon::parse(now())->format('d-m') => 0]);

        $allVisits = Visit::whereBetween('visited_at', [Carbon::parse(now())->startOfDay(), Carbon::now()->endOfDay()])
            ->orderBy('visited_at')
            ->get();

        $realVisitors = $this->filterRealVisits($allVisits);

        $visitors = $realVisitors
            ->groupBy(function ($v) {
                $date = $v->visited_at instanceof \Carbon\Carbon
                    ? $v->visited_at
                    : \Carbon\Carbon::parse((string) $v->visited_at);

                return $date->format('d-m');
            })
            ->map
            ->count();

        $primaryInfo = $dates->map(fn ($value, $date) => $visitors[$date] ?? 0)->toJson();
        $secondaryInfo = $this->filterVisitorsYesterdayPreviousPeriod();

        return json_encode([
            'primary' => $primaryInfo,
            'secondary' => $secondaryInfo,
        ]);
    }

    private function filterVisitorsYesterdayPreviousPeriod(): string
    {
        $dates = collect([Carbon::parse(now())->format('d-m') => 0]);

        $allVisits = Visit::whereBetween('visited_at', [Carbon::parse(now())->subDays(1)->startOfDay(), Carbon::now()->subDays(1)->endOfDay()])
            ->orderBy('visited_at')
            ->get();

        $realVisitors = $this->filterRealVisits($allVisits);

        $visitors = $realVisitors->groupBy(function ($visitor) {
            return Carbon::parse($visitor->visited_at)->format('d-m');
        })->map(function ($visitors) {
            return $visitors->count();
        });

        return $dates->map(fn ($value, $date) => $visitors[$date] ?? 0)->toJson();
    }

    /**
     * Generates a report of visitors for the last seven days.
     *
     * This method calculates the number of visitors grouped by each day within the past seven days. It initializes
     * a default collection of dates with zero values and retrieves visitor records created within the specified
     * seven-day period. The visitors are grouped by their creation dates and counted, populating the primary
     * dataset. Additionally, it appends data from the previous seven-day period to form a secondary data set.
     *
     * @return string A JSON-encoded array consisting of two datasets:
     *                'primary' (visitor data for the last seven days)
     *                and 'secondary' (historical data for the previous week).
     */
    private function filterVisitorsSevenDays()
    {
        $reportingPeriod = Carbon::parse(now())->subDays(7)->daysUntil(Carbon::parse(now()));

        $dates = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [Carbon::parse($date->toDateString())->format('d-m') => 0];
        });

        $allVisits = Visit::whereBetween('visited_at', [Carbon::parse(now())->subDays(7)->startOfDay(), Carbon::now()->endOfDay()])
            ->orderBy('visited_at')
            ->get();

        $realVisitors = $this->filterRealVisits($allVisits);

        $visitors = $realVisitors->groupBy(function ($visitor) {
            return Carbon::parse($visitor->visited_at)->format('d-m');
        })->map(function ($visitors) {
            return $visitors->count();
        });

        $primaryInfo = $dates->map(fn ($value, $date) => $visitors[$date] ?? null)->toJson();
        $secondaryInfo = $this->filterVisitorsPreviousSevenDays();

        return json_encode(['primary' => $primaryInfo, 'secondary' => $secondaryInfo]);
    }

    private function filterVisitorsPreviousSevenDays(): string
    {
        $reportingPeriod = Carbon::parse(now())->subDays(14)->daysUntil(Carbon::parse(now())->subDays(8));

        $dates = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [Carbon::parse($date->toDateString())->format('d-m') => 0];
        });

        $allVisits = Visit::whereBetween('visited_at', [Carbon::parse(now())->subDays(14)->startOfDay(), Carbon::parse(now())->subDays(8)->endOfDay()])
            ->orderBy('visited_at')
            ->get();

        $realVisitors = $this->filterRealVisits($allVisits);

        $visitors = $realVisitors->groupBy(function ($visitor) {
            return Carbon::parse($visitor->visited_at)->format('d-m');
        })->map(function ($visitors) {
            return $visitors->count();
        });

        return $dates->map(fn ($value, $date) => $visitors[$date] ?? 0)->toJson();
    }

    /**
     * Generates a JSON report of visitor data for the current month.
     *
     * This method calculates the number of visitors for each day of the current month up to the current date.
     * It initializes a date range from the start of the month and prepares a dataset with zero counts for each day.
     * The data is then populated with the actual visitor counts grouped by the day of their creation timestamp.
     * The method also includes a secondary dataset for the previous month's visitor data and combines both datasets
     * into a JSON-encoded response.
     *
     * @return string A JSON-encoded string containing the current month's visitor data as the primary information
     *                and the previous month's data as the secondary information.
     */
    private function filterVisitorsThisMonth(): string
    {
        $reportingPeriod = Carbon::parse(now())->startOfMonth()->daysUntil(now());

        $dates = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [Carbon::parse($date->toDateString())->format('d-m') => 0];
        });

        $allVisits = Visit::whereBetween('visited_at', [Carbon::parse(now())->startOfMonth(), Carbon::now()])
            ->orderBy('visited_at')
            ->get();

        $realVisitors = $this->filterRealVisits($allVisits);

        $visitors = $realVisitors->groupBy(function ($visitor) {
            return Carbon::parse($visitor->visited_at)->format('d-m');
        })->map(function ($visitors) {
            return $visitors->count();
        });

        $primaryInfo = $dates->map(fn ($value, $date) => $visitors[$date] ?? null)->toJson();
        $secondaryInfo = $this->filterVisitorsPreviousMonth();

        return json_encode(['primary' => $primaryInfo, 'secondary' => $secondaryInfo]);
    }

    private function filterVisitorsPreviousMonth(): string
    {
        $reportingPeriod = Carbon::now()->startOfMonth()->subMonth()->daysUntil(Carbon::now()->startOfMonth()->subMonth()->endOfMonth());

        $dates = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [Carbon::parse($date->toDateString())->format('d-m') => 0];
        });

        $allVisits = Visit::whereBetween('visited_at', [Carbon::now()->startOfMonth()->subMonth(), Carbon::now()->startOfMonth()->subMonth()->endOfMonth()])
            ->orderBy('visited_at')
            ->get();

        $realVisitors = $this->filterRealVisits($allVisits);

        $visitors = $realVisitors->groupBy(function ($visitor) {
            return Carbon::parse($visitor->visited_at)->format('d-m');
        })->map(function ($visitors) {
            return $visitors->count();
        });

        return $dates->map(fn ($value, $date) => $visitors[$date] ?? 0)->toJson();
    }

    /**
     * Filters and processes visitor data for the current year to generate a JSON response.
     *
     * This method calculates the reporting period for the current year, organizes the months
     * within that period, and retrieves visitors' data grouped by year and month. It generates
     * a primary dataset for the current year and appends secondary data for the previous year.
     *
     * @return string JSON-encoded response containing visitor statistics for the current year,
     *                including comparisons with the previous year.
     */
    private function filterVisitorsThisYear(): string
    {
        $reportingPeriod = Carbon::parse(now())->startOfYear()->daysUntil(now());

        $months = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [$date->year.' '.$date->monthName => 0];
        });

        $allVisits = Visit::whereBetween('visited_at', [Carbon::parse(now())->startOfYear(), Carbon::now()->endOfDay()])
            ->orderBy('visited_at')
            ->get();

        $realVisitors = $this->filterRealVisits($allVisits);

        $visitors = $realVisitors->groupBy(function ($visitor) {
            return Carbon::parse($visitor->visited_at)->format('Y F');
        })->map(function ($visitors) {
            return $visitors->count();
        });

        $primaryInfo = $months->map(fn ($value, $month) => $visitors[$month] ?? null)->toJson();
        $secondaryInfo = $this->filterVisitorsPreviousYear();

        return json_encode(['primary' => $primaryInfo, 'secondary' => $secondaryInfo]);
    }

    private function filterVisitorsPreviousYear(): string
    {
        $reportingPeriod = Carbon::parse(now())->startOfYear()->subYear(1)->daysUntil(now()->startOfYear()->subYear(1)->endOfYear());

        $months = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [$date->year.' '.$date->monthName => 0];
        });

        $allVisits = Visit::whereBetween('visited_at', [Carbon::parse(now())->startOfYear()->subYear(1), now()->startOfYear()->subYear(1)->endOfYear()])
            ->orderBy('visited_at')
            ->get();

        $realVisitors = $this->filterRealVisits($allVisits);

        $visitors = $realVisitors->groupBy(function ($visitor) {
            return Carbon::parse($visitor->visited_at)->format('Y F');
        })->map(function ($visitors) {
            return $visitors->count();
        });

        return $months->map(fn ($value, $month) => $visitors[$month] ?? 0)->toJson();
    }

    /**
     * Processes and compares visitor data over the past 12 months to generate a JSON report.
     *
     * This method calculates a reporting period spanning the previous 12 months, organizes
     * monthly data for that timeframe, and retrieves visitor statistics grouped by year and month.
     * It generates a primary dataset for the last 12 months and appends secondary data for comparison.
     *
     * @return string JSON-encoded response containing visitor statistics for the past year,
     *                including comparative data from the corresponding period of the previous year.
     */
    private function filterVisitorsYearOnYear(): string
    {
        $reportingPeriod = Carbon::parse(now())->subMonths(12)->monthsUntil(Carbon::parse(now()));

        $months = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [$date->year.' '.$date->monthName => 0];
        });

        $allVisits = Visit::whereBetween('visited_at', [Carbon::parse(now())->subMonths(12)->startOfMonth(), Carbon::now()])
            ->orderBy('visited_at')
            ->get();

        $realVisitors = $this->filterRealVisits($allVisits);

        $visitors = $realVisitors->groupBy(function ($visitor) {
            return Carbon::parse($visitor->visited_at)->format('Y F');
        })->map(function ($visitors) {
            return $visitors->count();
        });

        $primaryInfo = $months->map(fn ($value, $month) => $visitors[$month] ?? null)->toJson();
        $secondaryInfo = $this->filterVisitorsPreviousYearOnYearPeriod();

        return json_encode(['primary' => $primaryInfo, 'secondary' => $secondaryInfo]);
    }

    private function filterVisitorsPreviousYearOnYearPeriod(): string
    {
        $reportingPeriod = Carbon::parse(now())->startOfMonth()->subYear(2)->daysUntil(now()->startOfMonth()->subYear(1)->endOfMonth());

        $months = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [$date->year.' '.$date->monthName => 0];
        });

        $allVisits = Visit::whereBetween('visited_at', [Carbon::parse(now())->startOfMonth()->subYear(2), now()->startOfMonth()->subYear(1)->endOfMonth()])
            ->orderBy('visited_at')
            ->get();

        $realVisitors = $this->filterRealVisits($allVisits);

        $visitors = $realVisitors->groupBy(function ($visitor) {
            return Carbon::parse($visitor->visited_at)->format('Y F');
        })->map(function ($visitors) {
            return $visitors->count();
        });

        return $months->map(fn ($value, $month) => $visitors[$month] ?? 0)->toJson();
    }
}
