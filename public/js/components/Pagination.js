
class Pagination {

    /**
     * Contructor of a navbar (designed with the CSS of Bootstrap 3 and 4)
     * @param integer total
     * @param integer offset
     * @param integer by_page
     * @param string current_page
     * @param array param_page
     * @param boolean mvc
     * @param idnavbar
     * @param filter (blank by default)
     */
    constructor(total, offset, by_page, current_page, param_page, mvc, idnavbar, filter="") {
        this.total = Number(total);
        this.offset = Number(offset);
        this.by_page = Number(by_page);
        this.current_page = String(current_page);
        this.param_page = (typeof param_page == "object") ? param_page : {};
        this.mvc = Boolean(mvc);
        this.idnavbar = (idnavbar != undefined) ? String(idnavbar) : null;
        this.filter = String(filter).trim();
        this.btnlimits = {prev: "<<", next: ">>"};
    }

    /**
     * Constructor of a HTTP query
     * @param data
     * @param separator
     * @returns {string}
     */
    static httpBuildQuery(data, separator = "&") {
        var tmp_datas = [];
        for (let key of Object.keys(data)) {
            tmp_datas.push(key + '=' + encodeURI(data[key]));
        }
        return tmp_datas.join(separator);
    }

    /**
     * Converts a string to its html characters completely.
     * Source : https://ourcodeworld.com/articles/read/188/encode-and-decode-html-entities-using-pure-javascript
     * @param {String} str String with unescaped HTML characters
     **/
    static encodeHTML(str) {
        var buf = [];
        for (let i = str.length - 1; i >= 0; i--) {
            buf.unshift(['&#', str[i].charCodeAt(), ';'].join(''));
        }
        return buf.join('');
    }

    /**
     * Generate the HTML code (including LI and A tags)
     * @param inactive
     * @param text
     * @param offset
     * @param current_page
     * @param params_page
     * @param specif
     * @returns {string}
     */
    printLink(inactive, text, offset, current_page, params_page, specif = null) {

        // on prépare l'URL avec tous les paramètres sauf "offset"
        if (specif != null) {
            offset = specif;
        } else {
            if (offset == undefined || offset == '' || offset == '0') {
                offset = '1';
            }
        }
        var url = '';
        params_page ['offset'] = offset;
        if (this.mvc) {
            url = this.constructor.httpBuildQuery(params_page, "/");
        } else {
            url = '?' + this.constructor.httpBuildQuery(params_page);
        }
        var output = '';
        current_page = this.constructor.encodeHTML(current_page);

        let tmpclass = "";
        if (inactive) {
            tmpclass = "active";
        }
        output = `<li data-offset="${offset}" class="page-item ${tmpclass}"><a class="page-link" data-href="${current_page}${url}" href="#">${text}</a></li>\n`;

        return output;
    }

    /**
     * Generate an array (of links) wich will be used for construction of the navbar
     * @param total
     * @param offset
     * @param by_page
     * @param current_page
     * @param param_page
     * @returns {Array}
     */
    indexedLinks(total, offset, by_page, current_page, param_page) {

        var separator = ' | ';
        var list_links = [];
        list_links.push(this.printLink(offset == 1, this.btnlimits.prev, offset - by_page, current_page, param_page, 'prev'));

        var compteur = 0;
        var top_suspension = false;
        // affichage de tous les groupes à l'exception du dernier
        var start, end;
        for (start = 1, end = by_page; end < total; start += by_page, end += by_page) {
            compteur += 1;
            if (compteur < 7) {
                list_links.push(this.printLink(offset == start, `${start}`, start, current_page, param_page));
            } else {
                if (!top_suspension) {
                    top_suspension = true;
                    list_links.push('<li class="disabled"><a href="#"> ... </a></li>');
                }
            }
        }
        end = (total > start) ? '-' + total : '';

        list_links.push(this.printLink(offset == start, `${start}`, start, current_page, param_page));

        list_links.push(this.printLink(offset == start, this.btnlimits.next, offset + by_page, current_page, param_page, 'next'));

        return list_links;
    }

    /**
     * Final rendering of the navbar
     * @returns {string}
     */
    render() {
        if (this.offset == 0) {
            this.offset = 1;
        }
        var links = this.indexedLinks(this.total, this.offset, this.by_page, this.current_page, this.param_page);
        var idnavbar = '';
        if (this.idnavbar != null) {
            idnavbar = `data-idnavbar="${this.idnavbar}"`;
        }
        var html = `<nav aria-label="Page navigation" data-maxbypage="${this.by_page}" >`+
            `<ul class="pagination" ${idnavbar} data-offset="${this.offset}" data-filter="${this.filter}" data-total="${this.total}" >\n`;
        html += links.join('');
        html += '</ul></nav>\n';
        return html;
    }
}

