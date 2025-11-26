import { execSync } from "child_process";
import react from "@vitejs/plugin-react";
import fs from "fs";
import path from "path";
import colors from "picocolors";
import { fileURLToPath } from "url";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

export default {
  base: "/assets/app_hyup/react/dist/",
  build: {
    outDir: "dist",
  },
  plugins: [
    react(),
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

        // ============================================================
        // 1) assets 복사
        // ============================================================
        if (fs.existsSync(assetsSrc)) {
          fs.mkdirSync(path.dirname(assetsTarget), { recursive: true });

          try {
            execSync(`robocopy "${assetsSrc}" "${assetsTarget}" /E`, {
              stdio: "inherit",
            });
          } catch {
            console.log(colors.gray("ℹ robocopy non-zero exit ignored."));
          }

          fs.rmSync(assetsSrc, { recursive: true, force: true });
          console.log(colors.green("✔ assets moved successfully"));
        } else {
          console.log(colors.red("⚠ assets folder not found"));
        }

        // ============================================================
        // 2) index.html 이동 (경로 수정 필요 없음!)
        // ============================================================
        if (fs.existsSync(indexSrc)) {
          fs.mkdirSync(htmlTargetDir, { recursive: true });

          const indexTarget = path.join(htmlTargetDir, "index.html");

          // base 설정 때문에 추가적인 경로 변환 필요 X
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
