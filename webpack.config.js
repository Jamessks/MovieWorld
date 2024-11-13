const path = require("path");

module.exports = {
  entry: {
    main: "./public/src/js/main.js",
  },
  output: {
    path: path.resolve(__dirname, "public/dist"),
    filename: "[name].bundle.js",
  },
  resolve: {
    alias: {
      vue: "vue/dist/vue.esm-bundler.js",
    },
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: "vue-loader",
      },
      {
        test: /\.css$/,
        use: ["style-loader", "css-loader"],
      },
    ],
  },
  plugins: [new (require("vue-loader").VueLoaderPlugin)()],
  mode: "production",
};
