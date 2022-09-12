"use strict";

class NewsTableRowElement {

    /**
     * @param {News} data
     * */
    constructor(data) {
        this.news = data;
    }

    prepareRow() {
        const row = document.createElement("tr");

        const titleColumn = document.createElement("td");
        titleColumn.innerHTML = this.news.title;

        const dateColumn = document.createElement("td");
        dateColumn.innerHTML = this.news.date;

        const editColumn = document.createElement("td");
        const editLink = document.createElement("a");
        editLink.innerText = "EDYTUJ";

        const deleteColumn = document.createElement("td");
        const deleteLink = document.createElement("a");
        deleteLink.innerText = "USUN";

        editColumn.append(editLink);
        deleteColumn.append(deleteLink);


        row.append(titleColumn, dateColumn, editColumn, deleteColumn);

        return row;
    }

    /**
     * @param {HTMLElement} element
     * */
    render(element) {
        element.appendChild(this.prepareRow())
    }
}
