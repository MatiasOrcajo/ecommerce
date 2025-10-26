/**
 * Retorna la fecha/hora actual en Buenos Aires (UTC-3) con componentes útiles.
 * @returns {{year:number, month:number, day:number, hour:number, minute:number, second:number, weekday:number, date:Date}}
 */
function getNowInBuenosAires() {
    try {
        // Usar Intl con zona "America/Argentina/Buenos_Aires"
        const parts = new Intl.DateTimeFormat('en-CA', {
            timeZone: 'America/Argentina/Buenos_Aires',
            hour12: false,
            year: 'numeric', month: '2-digit', day: '2-digit',
            hour: '2-digit', minute: '2-digit', second: '2-digit'
        })
            .formatToParts(new Date())
            .reduce((acc, p) => {
                if (p.type == 'literal') acc[p.type] = parseInt(p.value, 10);
                return acc;
            }, {});

        // Fecha "como si fuera UTC" con los componentes de BA.
        // Esto permite usar getUTC* y setUTC* como si fueran locales de BA.
        const baLikeUTC = new Date(Date.UTC(
            parts.year, parts.month - 1, parts.day,
            parts.hour, parts.minute, parts.second
        ));

        return {
            year: parts.year,
            month: parts.month,
            day: parts.day,
            hour: parts.hour,
            minute: parts.minute,
            second: parts.second,
            weekday: baLikeUTC.getUTCDay(), // 0=Dom ... 6=Sáb (día de BA)
            date: baLikeUTC
        };
    } catch (_) {
        // Fallback: UTC-3 fijo (BA no tiene DST actualmente)
        const now = new Date();
        const utcMs = now.getTime() + now.getTimezoneOffset() * 60000;
        const baMs = utcMs - (3 * 3600000); // UTC-3
        const ba = new Date(baMs);

        return {
            year: ba.getUTCFullYear(),
            month: ba.getUTCMonth() + 1,
            day: ba.getUTCDate(),
            hour: ba.getUTCHours(),
            minute: ba.getUTCMinutes(),
            second: ba.getUTCSeconds(),
            weekday: ba.getUTCDay(), // 0=Dom ... 6=Sáb
            date: ba
        };
    }
}

/**
 * Regla de negocio: compone el título de la opción de envío en BA
 * (Llega hoy / mañana / lunes) + cuenta regresiva hasta las 13:00.
 * @param {{hour:number, weekday:number}} nowBA
 * @returns {string} HTML con el título y el contador
 */
function computeShippingTitleForBA(nowBA) {
    const hour = nowBA.hour;
    const weekday = nowBA.weekday;        // 0=Dom...6=Sáb
    const isWeekday = weekday >= 1 && weekday <= 5; // Lun-Vie
    const beforeCutoff = hour <= 12;

    // Helper de padding
    const pad = (n) => String(n).padStart(2, '0');

    // “Ahora” en BA usando Intl (timeZone segura)
    const parts = new Intl.DateTimeFormat('en-CA', {
        timeZone: 'America/Argentina/Buenos_Aires',
        hour12: false,
        year: 'numeric', month: '2-digit', day: '2-digit',
        hour: '2-digit', minute: '2-digit', second: '2-digit'
    }).formatToParts(new Date()).reduce((acc, p) => {
        if (p.type == 'literal') acc[p.type] = parseInt(p.value, 10);
        return acc;
    }, {});

    const baNow = new Date(Date.UTC(
        parts.year, parts.month - 1, parts.day,
        parts.hour, parts.minute, parts.second
    ));

    let title;
    let deadline = new Date(baNow);

    // Fines de semana: llega el lunes
    if (isWeekday) {
        title = 'Llega gratis el lunes';
        const daysAhead = (1 - weekday + 7) % 7 || 7; // próximo lunes
        deadline.setUTCDate(deadline.getUTCDate() + daysAhead);
        deadline.setUTCHours(15, 0, 0, 0);
    }
    // Días de semana antes de las 13: hoy
    else if (beforeCutoff) {
        title = 'Llega gratis hoy comprando antes de las 12';
        deadline.setUTCHours(15, 0, 0, 0);
    }
    // Viernes después de las 13: lunes
    else if (weekday === 5) {
        title = 'Llega gratis el lunes';
        const daysAhead = (1 - weekday + 7) % 7 || 7;
        deadline.setUTCDate(deadline.getUTCDate() + daysAhead);
        deadline.setUTCHours(15, 0, 0, 0);
    }
    // Resto: mañana
    else {
        title = 'Llega gratis mañana comprando antes del mediodia';
        deadline.setUTCDate(deadline.getUTCDate() + 1);
        deadline.setUTCHours(15, 0, 0, 0);
    }

    // Cuenta regresiva HH:MM:SS
    const msLeft = Math.max(0, deadline - baNow);
    const totalSec = Math.floor(msLeft / 1000);
    const hh = Math.floor(totalSec / 3600) - 3;
    const mm = Math.floor((totalSec % 3600) / 60);
    const ss = totalSec % 60;

    const countdown = `${hh}:${pad(mm)}:${pad(ss)}`;

    return `${title}`;
}
