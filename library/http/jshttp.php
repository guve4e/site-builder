<script>
    var http = (function(){

        let webservice = <?php echo $this->getWebServicesInfoForJS(); ?>;

        let urlLocal = webservice[0]['url_base_local'];
        let urlRemote = webservice[0]['url_base_remote'];

        let elements = [];
        let jsonKeys = [];
        let method = "GET";
        let mock = false;
        let async = false;
        let time = 0;
        let controller = "";
        let parameter = "";
        let refresh = false;
        let data = "";

        let makeMockUrl = (url) => {
            url = url + "/" + controller + ".json";
            return url
        };

        let constructUrl = () => {

            let url = urlRemote;

            if (mock)
                url = makeMockUrl(urlLocal);
            else {
                url = url + "/" + controller;
                if (parameter !== "")
                    url = url + "/" + parameter;
            }

            return url;
        };

        let send = () => {
            let url = constructUrl();

            let xmlHttp = new XMLHttpRequest();

            if (refresh > 0)
                xmlHttp.onreadystatechange = function () {
                    if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {

                        let json = JSON.parse(xmlHttp.responseText);

                        elements.forEach((_, i) => {
                            elements[i].innerHTML = json[jsonKeys[i]]
                        });

                        if (time > 0) {
                            // call send again, after certain time
                            setTimeout(() => { send(); }, time);
                        }
                    }
                };

            xmlHttp.open(method, url, async);
            xmlHttp.send(JSON.stringify(data));
            return xmlHttp.responseText;
        };

        let setMethod  = (verb) => {
            method = verb;
        };

        let setDataToSend = (body) => {
            data = body;
        };

        let setService = (service) => {
            controller = service;
        };

        let setParameter = (param) => {
            parameter = param;
        };

        let setTimer = (timeInSeconds) => {
            time = timeInSeconds;
            async = true;
        };

        let setAsync = (asyncBool) => {
            async = asyncBool;
        };

        let setRefresh = (ref) => {
            refresh = ref;
        };

        let setOutputElements = (els) => {
            els.forEach((_, i) => {
                elements[i] = document.getElementById(_);
            })
        };

        let setOutputElementReceivingJsonKeys = (keys) => {
            keys.forEach((_) => {jsonKeys.push(_)});
        };

        let isMock = (isMock) => {
            mock = isMock;
        };

        let setApi = (apiName) => {

            let api = webservice.filter(_ => _.name === apiName);

            if (api)
            {
                urlLocal = api[0].url_base_local;
                urlRemote = api[0].url_base_remote;
            }
        };

        return {
            setMethod: setMethod,
            setDataToSend: setDataToSend,
            setService: setService,
            setParameter: setParameter,
            setTimer: setTimer,
            setAsync: setAsync,
            setRefresh: setRefresh,
            setOutputElements: setOutputElements,
            setOutputElementReceivingJsonKeys: setOutputElementReceivingJsonKeys,
            isMock: isMock,
            setApi: setApi,
            send: send
        };
    })();
</script>
