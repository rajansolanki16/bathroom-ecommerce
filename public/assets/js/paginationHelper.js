function setupPaginatedTable({
    searchInputId,
    tableId,
    paginationWrapperId,
    paginationListId,
    prevBtnId,
    nextBtnId,
    noResultClass,
}) {
    document.addEventListener("DOMContentLoaded", () => {
        const searchInput = document.getElementById(searchInputId);
        const table = document.getElementById(tableId);
        const rows = Array.from(table.querySelectorAll("tbody tr"));
        const paginationList = document.getElementById(paginationListId);
        const prevBtn = document.getElementById(prevBtnId);
        const nextBtn = document.getElementById(nextBtnId);
        const paginationWrapper = document.getElementById(paginationWrapperId);
        const noResultEl = document.querySelector(`.${noResultClass}`);

        const rowsPerPage = 10;
        let currentPage = 1;

        const cleanText = (row) => {
            const clone = row.cloneNode(true);
            const lastTd = clone.querySelector("td:last-child");
            if (lastTd) lastTd.remove();
            clone.querySelectorAll("select").forEach((sel) => {
                const opt = sel.options[sel.selectedIndex];
                sel.innerHTML = "";
                if (opt) sel.appendChild(opt.cloneNode(true));
            });
            return clone.textContent.toLowerCase().trim();
        };

        const filterRows = () =>
            rows.filter((r) =>
                cleanText(r).includes(searchInput.value.toLowerCase())
            );

        const displayRows = (filtered) => {
            rows.forEach((r) => (r.style.display = "none"));
            const paginated = filtered.slice(
                (currentPage - 1) * rowsPerPage,
                currentPage * rowsPerPage
            );
            paginated.forEach((r) => (r.style.display = ""));
            const hasResults = filtered.length > 0;
            noResultEl.style.display = hasResults ? "none" : "block";
            paginationWrapper.style.display = hasResults ? "flex" : "none";
            prevBtn.style.display = hasResults ? "inline-block" : "none";
            nextBtn.style.display = hasResults ? "inline-block" : "none";
        };

        const createPageItem = (page, active = false) => {
            const li = document.createElement("li");
            li.className = `page-item ${active ? "active" : ""}`;
            li.innerHTML = `<a class="page-link" href="#">${page}</a>`;
            li.onclick = (e) => {

                e.preventDefault();
                currentPage = page;
                render();
            };
            return li;
        }; 

        const updatePagination = (filtered) => {
            const totalPages = Math.ceil(filtered.length / rowsPerPage);
            paginationList.innerHTML = "";
            prevBtn.classList.toggle("disabled", currentPage === 1);
            nextBtn.classList.toggle("disabled", currentPage >= totalPages);

            const range = 1;
            const addEllipsis = () => {
                const li = document.createElement("li");
                li.className = "page-item disabled";
                li.innerHTML = `<span class="page-link">...</span>`;
                paginationList.appendChild(li);
            }; 

            if (totalPages <= 5) {
                for (let i = 1; i <= totalPages; i++) {
                    paginationList.appendChild(
                        createPageItem(i, i === currentPage)
                    );
                }
            } else {
                paginationList.appendChild(
                    createPageItem(1, currentPage === 1)
                );
                if (currentPage > 2 + range) addEllipsis();
                for (
                    let i = Math.max(2, currentPage - range);
                    i <= Math.min(totalPages - 1, currentPage + range);
                    i++
                ) {
                    paginationList.appendChild(
                        createPageItem(i, i === currentPage)
                    );
                }
                if (currentPage < totalPages - 1 - range) addEllipsis();
                paginationList.appendChild(
                    createPageItem(totalPages, currentPage === totalPages)
                );
            }
        };

        const render = () => {
            const filtered = filterRows();
            const totalPages = Math.ceil(filtered.length / rowsPerPage);
            currentPage = Math.min(currentPage, totalPages || 1);
            displayRows(filtered);
            updatePagination(filtered);
        };

        searchInput.addEventListener("input", () => {
            currentPage = 1;
            render();
        });

        prevBtn.onclick = (e) => {
            e.preventDefault();
            if (!prevBtn.classList.contains("disabled")) {
                currentPage--;
                render();
            }
        };

        nextBtn.onclick = (e) => {
            e.preventDefault();
            const totalPages = Math.ceil(filterRows().length / rowsPerPage);
            if (
                !nextBtn.classList.contains("disabled") &&
                currentPage < totalPages
            ) {
                currentPage++;
                render();
            }
        };

        render();
    });
}
