const data = [
  {
    title: "Каталог товаров",
    open: true,
    children: [
      {
        title: "Мойки",
        children: [
          { title: "Ulgran", children: [{ title: "Smth" }, { title: "Smth" }] },
          { title: "Vigro Mramor" },
          { title: "Handmade", children: [{ title: "Smth" }, { title: "Smth" }] },
          { title: "Vigro Glass" },
        ],
      },
      {
        title: "Фильтры",
        children: [
          { title: "Ulgran", children: [{ title: "Smth" }, { title: "Smth" }] },
          { title: "Vigro Mramor" },
        ],
      },
    ],
  },
];

const root = document.querySelector("#list-items");
if (!root) {
  console.error("Не найден #list-items. Проверь id в HTML.");
}

function escapeHtml(str) {
  return String(str)
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#039;");
}

function renderTree(nodes) {
  return nodes
    .map((node) => {
      const hasChildren = Array.isArray(node.children) && node.children.length > 0;
      const isOpen = Boolean(node.open) && hasChildren;

      return `
        <div class="list-item ${isOpen ? "list-item_open" : ""}" ${hasChildren ? "data-parent" : ""}>
          <div class="list-item__inner">
            ${
              hasChildren
                ? `<button class="list-item__toggle" type="button" data-toggle aria-label="toggle">
                     <img class="list-item__arrow" src="img/chevron-down.png" alt="">
                   </button>`
                : `<span class="list-item__arrow-spacer"></span>`
            }
            <img class="list-item__folder" src="img/folder.png" alt="folder">
            <span>${escapeHtml(node.title)}</span>
          </div>

          ${hasChildren ? `<div class="list-item__items">${renderTree(node.children)}</div>` : ""}
        </div>
      `;
    })
    .join("");
}

if (root) {
  root.innerHTML = renderTree(data);
  console.log("rendered:", root.innerHTML.length, "chars");

  root.addEventListener("click", (e) => {
    const toggleBtn = e.target.closest("[data-toggle]");
    if (!toggleBtn) return;

    const item = toggleBtn.closest(".list-item[data-parent]");
    if (!item) return;

    item.classList.toggle("list-item_open");
  });
}