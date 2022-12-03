const path = require('path');

module.exports = {
  entry: {
    private: path.resolve(__dirname, './resources/js/administration/private.js')
  },
  mode: 'development',
  output: {
    path: path.resolve(__dirname, './public/'),
    filename: 'js/[name].js'
  },
  module:{
    rules: [
      {
        test: /\.js$/,
        use: 'babel-loader',
        exclude: /node_modules/
      },
      {
        test: /\.scss$/,
        use: [
          'style-loader',
          {
            loader: "css-loader",
            options: { 
              sourceMap: false
            },
          },
          'sass-loader'
        ],
        exclude: /node_modules/
      }
    ]
  }
};
