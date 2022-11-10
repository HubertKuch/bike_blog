"use strict";

class UserController {

    static baseUrl = "api";

    /**
     * @param {FormData} formData
     * */
    static async login(formData) {
        return await this.fetchData(`${this.baseUrl}/v2/users/login`, {
            method: 'POST',
            body: formData
        });
    }

    /**
     * @param {string} url
     * @param options
     * @return Promise<any>
     * */
    static async fetchData(url, options = {}) {
        const res = await fetch(url, options);

        return await res.json();
    }
}
