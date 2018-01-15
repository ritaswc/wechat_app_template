let T = {}
T.locale = null
T.locales = {}

T.registerLocale = function (locales) {
    T.locales = locales;
}

T.setLocale = function (code) {
    T.locale = code
}

T._ = function (line, data) {
    const locale = T.locale
    const locales = T.locales
    if (locale && locales[locale] && locales[locale][line]) {
        line = locales[locale][line]
    }

    return line
}

export default T
