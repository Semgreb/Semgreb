import requests

class OWASPZapAPI:
    def __init__(self, base_url, api_key):
        self.base_url = base_url
        self.api_key = api_key

    def _make_request(self, url, params=None):
        response = requests.get(url, params=params)
        response.raise_for_status()
        return response

    def spider_scan(self, target, recurse=""):
        url = self.base_url + f"/JSON/spider/action/scan/?apikey={self.api_key}&url={target}&contextName=&recurse={recurse}"
        return self._make_request(url)

    def ajax_spider_scan(self, target, in_scope="", context_name="", subtree_only=""):
        url = self.base_url + f"/JSON/ajaxSpider/action/scan/?apikey={self.api_key}&url={target}&inScope={in_scope}&contextName={context_name}&subtreeOnly={subtree_only}"
        return self._make_request(url)

    def spider_status(self, scan_id):
        url = self.base_url + f"/JSON/spider/view/status/?apikey={self.api_key}&scanId={scan_id}"
        return self._make_request(url)

    def active_scan(self, target, recurse=True, in_scope_only="", scan_policy_name="", method="", post_data="", context_id=""):
        url = self.base_url + f"/JSON/ascan/action/scan/?apikey={self.api_key}&url={target}&recurse={recurse}&inScopeOnly={in_scope_only}&scanPolicyName={scan_policy_name}&method={method}&postData={post_data}&contextId={context_id}"
        return self._make_request(url)

    def alerts_active_scan(self, target, start=0, count=100):
        url = self.base_url + f"/JSON/core/view/alerts/?apikey={self.api_key}&baseurl={target}&start={start}&count={count}"
        return self._make_request(url)

    def active_scan_status(self, target, scan_id):
        url = self.base_url + f"/JSON/ascan/view/status/?apikey={self.api_key}&baseurl={target}&scanId={scan_id}"
        return self._make_request(url)

    def stop_active_scan(self, scan_id):
        url = self.base_url + f"/JSON/ascan/action/stop/?apikey={self.api_key}&scanId={scan_id}"
        return self._make_request(url)

    def ajax_spider_status(self):
        url = self.base_url + "/JSON/ajaxSpider/view/status/?apikey={}".format(self.api_key)
        return self._make_request(url)



if __name__ == "__main__":
    base_url = "http://localhost:3001"
    api_key = "123456789"
    zap_api = OWASPZapAPI(base_url, api_key)
    target_url = "https://hackstore.rs/"

    # Example: Spider scan
    # spider_result = zap_api.spider_scan(target_url, recurse="true")
    # print("Spider Scan Result:", spider_result.json())

    # # Example: Ajax Spider scan
    # ajax_spider_result = zap_api.ajax_spider_scan(target_url, in_scope="", context_name="", subtree_only="")
    # print("Ajax Spider Scan Result:", ajax_spider_result.text)

    # # Example: Spider status
    # scan_id = "0"  # Replace with the actual scan ID
    # spider_status_result = zap_api.spider_status(scan_id)
    # print("Spider Status Result:", spider_status_result.text)

    # # Example: Active scan
    # active_scan_result = zap_api.active_scan(target_url)
    # print("Active Scan Result:", active_scan_result.json())

    # # Example: Alerts for Active scan
    # alerts_active_scan_result = zap_api.alerts_active_scan(target_url, start=0, count=100)
    # print("Alerts for Active Scan Result:", alerts_active_scan_result.text)

    # # Example: Active scan status
    # active_scan_status_result = zap_api.active_scan_status(target_url, 0)
    # print("Active Scan Status Result:", active_scan_status_result.json())

    # # Example: Stop Active scan
    # stop_active_scan_result = zap_api.stop_active_scan(0)
    # print("Stop Active Scan Result:", stop_active_scan_result.text)

    # # Example: Ajax Spider status
    # ajax_spider_status_result = zap_api.ajax_spider_status()
    # print("Ajax Spider Status Result:", ajax_spider_status_result.text)

