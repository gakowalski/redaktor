import {nodeResolve} from "@rollup/plugin-node-resolve"

export default {
  input: "./js/editor.js",
  output: {
    file: "./js/editor.bundle.js",
    format: "iife"
  },
  plugins: [ nodeResolve() ]
}
