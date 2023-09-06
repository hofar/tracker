const path = require('path');

module.exports = {
    entry: {
        template: './assets/js/template.js',
        programkerjaData: './assets/js/programkerjaData.js',
        scanner: './assets/js/scanner.js',
        generator: './assets/js/generator.js'
    },
    output: {
        filename: '[name].bundle.js',
        path: path.resolve(__dirname, './assets/dist')
    },
    module: {
        rules: [
            // Add rule to handle Bootstrap's CSS files
            {
                test: /bootstrap\.css$/,
                use: ['style-loader', 'css-loader'],
            },
            // Add rule to handle Bootstrap Icons' CSS files
            {
                test: /bootstrap-icons\.css$/,
                use: ['style-loader', 'css-loader'],
            },
            // Add rule to handle DataTables' CSS files with ignore-loader
            // {
            //     test: /dataTables\.css$/,
            //     include: path.resolve(__dirname, 'node_modules/datatables.net-bs5/css'),
            //     use: 'ignore-loader',
            // },
            // Add rule to handle Bootstrap's JavaScript files
            {
                test: /bootstrap\.js$/,
                use: 'expose-loader/bootstrap', // Expose Bootstrap to global environment
            },
        ],
    },
    mode: 'production', // or 'development' for development mode
};
