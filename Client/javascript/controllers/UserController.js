"use strict";

class UserController {

    static baseUrl = "api/v1/users";

    /**
     * @param {FormData} formData
     * */
    static async login(formData) {
        return await this.fetchData(`${this.baseUrl}/login`, {
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
