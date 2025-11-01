import { execSync } from "child_process";
import fs from "fs";
import path from "path";
import colors from "picocolors";
import { fileURLToPath } from "url";
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

export default {
  build: {
    outDir: "dist",
  },
  plugins: [
    {
      name: "move-build-files",
      closeBundle() {
        const distPath = path.resolve(__dirname, "dist");
        const assetsSrc = path.join(distPath, "assets");
        const assetsTarget =
          "C:\\laragon\\www\\jmtech\\assets\\app_hyup\\react\\dist\\assets";
        const indexSrc = path.join(distPath, "index.html");
        const htmlTargetDir =
          "C:\\laragon\\www\\jmtech\\app_hyup\\views\\React\\dist";

        console.log(
          colors.cyan("\n🚀 [Build] Moving build results (robocopy)...\n")
        );

        // ✅ 1️⃣ assets 이동
        if (fs.existsSync(assetsSrc)) {
          fs.mkdirSync(path.dirname(assetsTarget), { recursive: true });
          try {
            // robocopy는 0이 아니어도 성공이므로 오류 무시
            execSync(`robocopy "${assetsSrc}" "${assetsTarget}" /E`, {
              stdio: "inherit",
            });
          } catch (error) {
            console.log(
              colors.gray("ℹ robocopy finished (non-zero exit code, ignored).")
            );
          }
          fs.rmSync(assetsSrc, { recursive: true, force: true });
          console.log(colors.green("✔ assets moved successfully"));
        } else {
          console.log(colors.red("⚠ assets folder not found"));
        }

        // ✅ 2️⃣ index.html 이동
        if (fs.existsSync(indexSrc)) {
          fs.mkdirSync(htmlTargetDir, { recursive: true });
          const indexTarget = path.join(htmlTargetDir, "index.html");

          let html = fs.readFileSync(indexSrc, "utf-8");
          html = html.replaceAll(
            /(src|href)="\/?assets\//g,
            '$1="/assets/app_hyup/react/dist/assets/'
          );
          fs.writeFileSync(indexSrc, html);

          execSync(`move /Y "${indexSrc}" "${indexTarget}"`);
          console.log(colors.green("✔ index.html moved successfully"));
        } else {
          console.log(colors.red("⚠ index.html not found"));
        }

        console.log(colors.cyan("\n✅ Build move completed!\n"));
      },
    },
  ],
};
