// webpack.config.js
const path = require("path");

const makeConfig = ({
  name,
  entry,
  outDir,
  mode = process.env.NODE_ENV || "development",
}) => ({
  name,
  mode,
  entry,
  output: {
    filename: "build.js",
    path: path.resolve(__dirname, outDir),
    clean: true,
  },
  module: {
    rules: [
      { test: /\.(js|jsx)$/, exclude: /node_modules/, use: "babel-loader" },
      {
        test: /\.(png|jpe?g|gif|webp|avif)$/i,
        type: "asset", // auto inline small files, file output for larger ones
        parser: { dataUrlCondition: { maxSize: 8 * 1024 } }, // 8 KB inline threshold
      },
      {
        test: /\.svg$/i,
        type: "asset",
        parser: { dataUrlCondition: { maxSize: 8 * 1024 } },
      },
      {
        test: /\.(woff2?|eot|ttf|otf)$/i,
        type: "asset/resource",
      },
    ],
  },
  resolve: {
    extensions: [".js", ".jsx"],
    alias: {
      "@": path.resolve(__dirname), // <-- root alias
    },
  },
  devtool: mode === "production" ? false : "source-map",
  optimization: { minimize: mode === "production" },
});

module.exports = [
  makeConfig({
    name: "admin",
    entry: "./admin/src/index.js",
    outDir: "admin/dist",
  }),
  makeConfig({
    name: "public",
    entry: "./public/src/index.js",
    outDir: "public/dist",
  }),
];
