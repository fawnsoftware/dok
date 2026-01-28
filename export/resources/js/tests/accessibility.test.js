// accessibility.test.ts TODO
import { describe, it, expect, beforeAll, afterAll } from "vitest";
import { chromium } from "playwright";
import { injectAxe, checkA11y } from "axe-playwright";

let browser;
let page;

const APP_URL = process.env.APP_URL;

const pages = [
    "/",
    "/prose",
    "/form",
    "/dok/3.x",
];

beforeAll(async () => {
    browser = await chromium.launch();
    page = await browser.newPage();
});

afterAll(async () => {
    await browser.close();
});

describe("AXE-CORE", () => {
    pages.forEach((uri) => {
        it(`${APP_URL}${uri}`, async () => {
            const response = await page.goto(`${APP_URL}${uri}`, {
                waitUntil: "networkidle",
                testTimeout: 30000,
            });

            if (!response || response.status() != 200) {
                throw new Error(`Page returned 404: ${APP_URL}${uri}`);
            }

            await injectAxe(page);
            await checkA11y(page, null, {
                axeOptions: {
                    iframes: false,
                    runOnly: {
                        type: "tag",
                        values: [
                            "wcag21aa",
                            "wcag22aa",
                            "best-practice",
                            "EN-301-549",
                        ],
                    },
                },
                detailedReport: true,
                detailedReportOptions: { html: true },
            });
        });
    });
});
