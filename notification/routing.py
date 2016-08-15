from channels.routing import route
from notification.consumers import ws_message, ws_connect, ws_disconnect, msg_consumer

channel_routing = [
    route("websocket.connect", ws_connect),
    route("websocket.receive", ws_message),
    route("websocket.disconnect", ws_disconnect),
    route("lobby-messages", msg_consumer)
]
