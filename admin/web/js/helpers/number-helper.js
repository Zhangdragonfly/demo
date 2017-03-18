/**
 *
 * @param number 要四舍五入的数
 * @param prec 要保留的小数位数
 * @returns {number}
 */
function format_decimal_number(number, prec) {
    var vv = Math.pow(10, prec);
    return Math.round(number * vv) / vv;
}
