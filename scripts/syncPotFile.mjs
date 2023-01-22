import fs from "fs-extra";
import path from "path";

const foldersToSearch = ["app", "resources"];
const resourceFilepath = "resources/lang/sage.pot";
const regex = /__\('(.+?)',\s'sage'\)/;
let items = [];
const translationKeys = new Set();

const extractKeyWords = (fileContents) => fileContents.match(regex) ?? [];
const removeSearchPart = (string) =>
  string.replace(/^__\('/, "").replace(/', 'sage'\)$/, "");

const getFileContentsRecursively = async (folderPath) => {
  let result = [];
  const files = await fs.readdir(folderPath);

  for (const file of files) {
    const filePath = path.join(folderPath, file);
    const stats = await fs.stat(filePath);

    if (stats.isDirectory()) {
      // Recursively search for files in subdirectory
      result = [...result, ...(await getFileContentsRecursively(filePath))];
    } else if (stats.isFile()) {
      result.push(await fs.readFile(filePath, "utf-8"));
    }
  }

  return result;
};

// Goes through files in a folder recursively and reads each file contents and extracts items
for (const dir of foldersToSearch) {
  const fileContents = await getFileContentsRecursively(dir);
  for (const fileContent of fileContents) {
    items = [
      ...items,
      ...extractKeyWords(fileContent).map((item) => removeSearchPart(item)),
    ];
  }
}

let potContent = `
msgid ""
msgstr ""

`;

items.map((current) => {
  if (translationKeys.has(current)) {
    return;
  }

  potContent += `msgid "${current}"\n`;
  potContent += `msgstr ""\n\n`;
  translationKeys.add(current);
});

await fs.ensureFile(resourceFilepath);
await fs.writeFile(resourceFilepath, potContent);
