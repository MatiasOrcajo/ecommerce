import {renderVisitorsChart} from "./getVisitorsInfo.js";
import {renderSalesChart} from "./getSalesInfo.js";

$("#time-filter").on("change", function (e) {
    // Obtener el valor seleccionado
    const selectedValue = $(this).val();
    renderVisitorsChart('/api/visitors?filter=' + selectedValue);
    renderSalesChart('/api/sales?filter=' + selectedValue);
});
