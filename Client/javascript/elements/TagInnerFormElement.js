"use strict";

class TagInnerFormElement {

    getContainer() {
        const container = document.createElement("div");

        container.classList.add("tag");

        return container;
    }

    getTagInput(tag) {
        const input = ElementsUtils.getInput("text");
        input.name = "tag-name";
        input.value = tag;

        return ElementsUtils.appendInLabel(input, "Tag");
    }

    render(element, tag = "") {
        element.append(this.get(tag))
    }

    get(tag) {
        const container = this.getContainer();
        const removeButton = ElementsUtils.getRemoveButton();

        removeButton.addEventListener("click", () => {
            removeButton.parentElement.remove()
        });

        container.append(this.getTagInput(tag), removeButton)

        return container;
    }
}
