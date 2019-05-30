function numberWithCommas(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");

    return parts.join(",");

}


Vue.filter('price', value => {
    if (value % 1 != 0) {
        return numberWithCommas(value.toFixed(2));
    }
    return value;
});

Vue.filter('ucFirst', val => {
    val = val.toLowerCase();

    return val.charAt(0).toUpperCase() + val.slice(1);
});
Vue.filter('uc', val => {
    return val.toUpperCase();
});

Vue.filter('datetime', val => {
    return moment(val).format('DD/MM/YYYY H:mm');
});
Vue.filter('date', val => {
    return moment(val).format('DD/MM/YYYY');
});
