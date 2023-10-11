/**
 * Project: damirifa
 * File: init.js
 * Author: Pennycodes
 * Organization: Colorbrace LLC
 * Author URI: https://colorbrace.com/staff/pennycodes
 * Created: 7/14/2022 at 7:01 PM.
 *
 * Copyright (c) 2022 Colorbrace LLC. All rights reserved.
 */
"use strict";

let notyf;

const themeColors = {
    primary: "#B8000C",
    primaryMedium: "#d4b3ff",
    primaryLight: "#f4edfd",
    secondary: "#830009",
    accent: "#797bf2",
    success: "#06d6a0",
    info: "#039BE5",
    warning: "#faae42",
    danger: "#FF7273",
    purple: "#8269B2",
    blue: "#37C3FF",
    green: "#93E088",
    yellow: "#FFD66E",
    orange: "#FFA981",
    lightText: "#a2a5b9",
    fadeGrey: "#ededed",
};

window.onload = function () {
    notyf = new Notyf({
        duration: 2000,
        position: {
            x: 'right',
            y: 'bottom',
        },
        types: [
            {
                type: 'warning',
                background: themeColors.warning,
                icon: {
                    className: 'fas fa-hand-paper',
                    tagName: 'i',
                    text: ''
                }
            },
            {
                type: 'info',
                background: themeColors.info,
                icon: {
                    className: 'fas fa-info-circle',
                    tagName: 'i',
                    text: ''
                }
            },
            {
                type: 'primary',
                background: themeColors.primary,
                icon: {
                    className: 'fas fa-car-crash',
                    tagName: 'i',
                    text: ''
                }
            },
            {
                type: 'accent',
                background: themeColors.accent,
                icon: {
                    className: 'fas fa-car-crash',
                    tagName: 'i',
                    text: ''
                }
            },
            {
                type: 'purple',
                background: themeColors.purple,
                icon: {
                    className: 'fas fa-check',
                    tagName: 'i',
                    text: ''
                }
            },
            {
                type: 'blue',
                background: themeColors.blue,
                icon: {
                    className: 'fas fa-check',
                    tagName: 'i',
                    text: ''
                }
            },
            {
                type: 'green',
                background: themeColors.green,
                icon: {
                    className: 'fas fa-check',
                    tagName: 'i',
                    text: ''
                }
            },
            {
                type: 'orange',
                background: themeColors.orange,
                icon: {
                    className: 'fas fa-check',
                    tagName: 'i',
                    text: ''
                }
            }
        ]
    });
}

function updateUrlQueryParam(key, value) {
    // Get the current URL
    let url = window.location.href;

    // Parse the URL and get the query string
    let queryString = url.split('?')[1];

    // If there are no existing query parameters, add the new key-value pair
    if (!queryString) {
        return url + '?' + encodeURIComponent(key) + '=' + encodeURIComponent(value);
    }

    // Parse the existing query string and store the key-value pairs in an object
    let params = {};
    queryString.split('&').forEach(param => {
        let [paramKey, paramValue] = param.split('=');
        params[decodeURIComponent(paramKey)] = decodeURIComponent(paramValue);
    });

    // If the new key already exists in the query string, replace its value with the new value or add it
    params[key] = value;

    // Build the new query string
    let newQuery = Object.keys(params)
        .map(paramKey => encodeURIComponent(paramKey) + '=' + encodeURIComponent(params[paramKey]))
        .join('&');

    // Replace the existing query string with the new one
    return url.replace(queryString, newQuery);
}
