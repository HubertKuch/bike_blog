"use strict";

class NewsSerializer {

    /** @return News */
    static serialize(data) {
        return new News(data);
    }

    /**
     * @param {News} news
     * @return {{date, description, title, tags}}
     * */
    static deserialize({title, date, tags, description}) {
        return {title, date, tags, description};
    }
}
