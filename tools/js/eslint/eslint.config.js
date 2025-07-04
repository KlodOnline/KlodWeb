import { defineConfig } from "eslint/config";
import js from "@eslint/js";
import prettierPlugin from "eslint-plugin-prettier";
import eslintConfigPrettier from "eslint-config-prettier";

export default defineConfig([
  {
    files: ["**/*.js"],
    plugins: {
      js,
      prettier: prettierPlugin,
    },
    extends: [
      "js/recommended",
      eslintConfigPrettier,
    ],
    rules: {
      "no-unused-vars": "warn",
      "no-undef": "warn",
      "prettier/prettier": "error"
    },
  },
]);