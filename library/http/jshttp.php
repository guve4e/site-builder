<script>
    class JSHttp {

        constructor() {
            this.webservice = <?php echo $this->getPrimaryWebServiceInfoForJS(); ?>;

            this.urlLocal = this.webservice[0]['url_base_local'];
            this.urlRemote = this.webservice[0]['url_base_remote'];

            this.mock = false;
            this.async = false;
            this.time = 0;

            this.elements = [];
            this.jsonKeys = [];
            this.method = "GET";

            this.controller = "";
            this.parameter = "";
            this.refresh = false;
            this.data = "";

            return this;
        }

        makeMockUrl(url) {
            url = url + "/" + this.controller + ".json";
            return url
        }

        constructUrl() {
            let url = this.urlRemote;

            if (this.mock)
                url = this.makeMockUrl(this.urlLocal);
            else {
                url = url + "/" + this.controller;
                if (this.param)
                    url = url + "/" + this.param;
            }

            return url;
        }

        send() {
            let url = this.constructUrl();

            let xmlHttp = new XMLHttpRequest();

            if (this.refresh > 0)
                xmlHttp.onreadystatechange = () => {
                    if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {

                        let json = JSON.parse(xmlHttp.responseText);

                        this.elements.forEach((_, i) => {
                            this.elements[i].innerHTML = json[this.jsonKeys[i]]
                        });


                        if (this.time > 0) {
                            // call send again, after certain time
                            setTimeout( () =>  { this.send(); }, this.time);
                        }
                    }
                };

            xmlHttp.open(this.method, url, this.async);
            xmlHttp.send(JSON.stringify(this.data));
            return xmlHttp.responseText;
        }

        setMethod(method) {
            this.method = method;
            return this
        }

        setDataToSend(data) {
            this.data = data;
            return this
        }

        setService(controller) {
            this.controller = controller;
            return this
        }

        setParameter(param) {
            this.param = param;
            return this
        }

        setTimer(time) {
            this.time = time;
            this.async = true;
            return this;
        }

        setAsync(async) {
            this.async = async;
            return this;
        }

        setRefresh(refresh) {
            this.refresh = refresh;
            return this;
        }

        setOutputElement(els) {
            els.forEach((_, i) => {
                this.elements[i] = document.getElementById(_);
            });
            return this;
        }

        setOutputElementReceivingJsonKey(keys) {
            keys.forEach((_) => {this.jsonKeys.push(_)});
            return this;
        }

        isMock(mock) {
            this.mock = mock;
            return this
        }

        setApi(apiName) {

            let api = this.webservice.filter(_ => _.name === apiName);

            if (api)
            {
                this.urlLocal = api[0].url_base_local;
                this.urlRemote = api[0].url_base_remote;
            }
            return this
        }
    }
</script>
