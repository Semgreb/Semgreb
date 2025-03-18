from zap_api_client.ZapApiClient import OWASPZapAPI
from zap_api_client.ZapContainerManager import ZapContainerManager
from time import sleep

CONTAINER_WAIT_TIMEOUT = 45

def create_vulnerability_scan(container_name, container_port, api_key, target_url):
    API_BASE_URL = "http://localhost:"
    print("***create_vulnerability_scan***")
    manager = ZapContainerManager()
    API_BASE_URL = API_BASE_URL + container_port
    zap_api = OWASPZapAPI(API_BASE_URL, api_key)
    try:
        print("***run_zap_container***")
        manager.run_zap(container_name, api_key, container_port)
        sleep(CONTAINER_WAIT_TIMEOUT)

        print("run spider")
        # Spider scan
        spider_result = zap_api.spider_scan(target_url, recurse="")
        print("Spider Scan Result:", spider_result.json())

        sleep(5)

        active_scan_result = zap_api.active_scan(target_url)
        print("Active Scan Result:", active_scan_result.json())
    except Exception as e:
        print(e)
        try:
            manager.delete_container_by_name(container_name)
        except:
            pass



def delete_containers_by_tag(tag):
    manager = ZapContainerManager()
    manager.delete_containers_by_tag(tag)


