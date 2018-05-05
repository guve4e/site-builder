<?php

?>

<script>

    class JSHttp {

        constructor() {

            this.webservice = <?php echo $this->getPrimaryWebServiceInfoForJS(); ?>;
            this.urlLocal = this.webservice['url_base_local'];
            this.urlRemote = this.webservice['url_base_remote'];
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

            let  xmlHttp = new XMLHttpRequest();
            xmlHttp.open(this.method, url, false); // true for asynchronous request
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

        isMock(mock) {
            this.mock = mock;
            return this
        }
    }
</script>