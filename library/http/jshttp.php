<script>
    class JSHttp {

        constructor() {
            this.webservice = <?php echo $this->getPrimaryWebServiceInfoForJS(); ?>;
            this.urlLocal = this.webservice['url_base_local'];
            this.urlRemote = this.webservice['url_base_remote'];
            this.mock = false;
            this.async = false;
            this.time = 0;

            return this;
        }

        makeMockUrl(url) {
            url = url + "/" + this.controller + ".json";
            return url
        }

        send() {
            let url = this.urlRemote;

            if (this.mock)
                url = this.makeMockUrl(this.urlLocal);
            else {
                url = url + "/" + this.controller;
                if (this.param)
                    url = url + "/" + this.param;
            }

            let localElement = this.element;
            let localTime = this.time;
            let that = this;

            let xmlHttp = new XMLHttpRequest();

            if (this.time > 0)
                xmlHttp.onreadystatechange = function () {

                    if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {
                        localElement.innerHTML = xmlHttp.responseText;

                        // call send again, after certain time
                        setTimeout(function() { that.send(); }, localTime);
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

        setElement(element) {
            this.element = document.getElementById(element);
            return this;
        }

        isMock(mock) {
            this.mock = mock;
            return this
        }
    }
</script>