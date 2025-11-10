import bottle
from bottle import route, run, request, response
import json
import os

@route('/flights', method='POST')
def receive_flight_data():
    try:
        flights_raw = request.forms.get("data")

        if flights_raw is None:
            response.status = 400
            return {"error": "No data received"}

        # Encoding-Fix: korrigiere falsch kodierte Umlaute
        flights_fixed = flights_raw.encode("latin1").decode("utf-8")

        flight_info = json.loads(flights_fixed)
        save_path = os.path.expanduser("~/public_html/flights/dashboard.json")

        with open(save_path, "w", encoding="utf-8") as f:
            json.dump({"flight_info": flight_info}, f, ensure_ascii=False, indent=2)

        print(f"Saved {len(flight_info)} flights to {save_path}")
        return bottle.HTTPResponse(
            status=200,
            body=json.dumps({"message": "Flight data saved."}),
            header={'Content-Type': 'application/json'}
        )

    except Exception as e:
        response.status = 500
        return {"error": str(e)}

if __name__ == '__main__':
    run(host='::', port=8081)
