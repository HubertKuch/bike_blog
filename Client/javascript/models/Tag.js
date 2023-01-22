"use strict";

class Tag {

    /**
     * @param {{ id: string, tag: string, descriptor: string, category: { id: string, category: string } }} props
     * */
    constructor(props) {
        this.id = props.id;
        this.tag = props.tag;
        this.descriptor = props.descriptor;
        this.category = props.category;
    }
}