'use strict';

module.exports = {
    devtool: 'inline-source-map',
    entry: './app/app.ts',
    output: { filename: '../assets/app.js' },
    module: {
        rules: [
            {
                test: /\.tsx?$/,
                loader: 'ts-loader'
            }
        ]
    },
    resolve: {
        extensions: [ '.ts', '.tsx', '.js' ],
        alias: {
           'vue$': 'vue/dist/vue.esm.js'
        }

    }
};

