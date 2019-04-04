const path = require('path');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const devMode = process.env.NODE_ENV !== 'production';

module.exports  = {
    entry: {
        'app-script': path.resolve(__dirname, './frontend/web/js/app-script.js'),
        '1-script': path.resolve(__dirname, './frontend/web/js/1-script.js'),
        '2-script': path.resolve(__dirname, './frontend/web/js/2-script.js'),
        '3-script': path.resolve(__dirname, './frontend/web/js/3-script.js'),
        '4-script': path.resolve(__dirname, './frontend/web/js/4-script.js'),
        'app-style': path.resolve(__dirname, './frontend/web/css/app-style.css'),
        '1-style': path.resolve(__dirname, './frontend/web/css/1-style.css'),
        '2-style': path.resolve(__dirname, './frontend/web/css/2-style.css'),
        '3-style': path.resolve(__dirname, './frontend/web/css/3-style.css'),
        '4-style': path.resolve(__dirname, './frontend/web/css/4-style.css'),
    },
    output: {
        filename: '[name].js',
        path: path.resolve(__dirname, './frontend/web/bundle'),
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: [/node_modules/],
                use: [
                    {
                        loader: 'babel-loader',
                        options: { presets: ['latest'] }
                    }
                ]
            },
            {
                test: /\.css$/,
                use: [
                	MiniCssExtractPlugin.loader,
                    {loader: 'css-loader', options: {minimize: true, sourceMap: true}},
                ]
            }
        ]
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: "[name].css",
            chunkFilename: "[id].css"
        })
    ],
    devtool: 'source-map'
};
