"use strict";

class YearContainerElement {

    /**
     * @param {NewsGroup} newsGroup
     * */
    constructor(newsGroup) {
        this.newsGroup = newsGroup;
    }

    prepareContainer() {
        const container = document.createElement("div");

        container.classList.add("news-navigation__year-container");

        return container;
    }

    prepareYearSpan() {
        const span = document.createElement("span");

        span.innerText = `${this.newsGroup.year}`;
        span.classList.add("year");

        return span;
    }

    prepareNewsList() {
        const list = document.createElement("ul");

        list.classList.add("news");

        const newsElements = this.newsGroup.news.map(news => this.prepareSingleNews(news));

        newsElements.forEach(newsElement => list.appendChild(newsElement));

        return list;
    }

    /**
     * @param {News} news
     * */
    prepareSingleNews(news) {
        const li = document.createElement("li");
        const link = document.createElement("a");

        link.href = `news?id=${news.id}`;
        link.innerHTML = news.title;

        li.appendChild(link);

        return li;
    }

    /**
     * @param {HTMLElement} element
     * */
    render(element) {
        const container = this.prepareContainer();
        const year = this.prepareYearSpan();
        const list = this.prepareNewsList();

        container.append(year, list);

        element.append(container);
    }
}
