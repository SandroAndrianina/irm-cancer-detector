package com.irm.scan.infrastructure.http;

import com.irm.scan.application.ActivityNotifier;
import com.irm.scan.domain.model.Scan;
import com.irm.scan.infrastructure.json.JsonMapper;

import java.net.URI;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.util.Map;

/**
 * Calls CI4 POST /api/logs. Failures are logged, never thrown
 * (a notification failure must not roll back a saved scan).
 */
public class CiActivityNotifier implements ActivityNotifier {

    private final String baseUrl;
    private final HttpClient http = HttpClient.newHttpClient();

    public CiActivityNotifier(String baseUrl) {
        this.baseUrl = baseUrl;
    }

    @Override
    public void notifyCreated(Scan scan) {
        try {
            String body = JsonMapper.GSON.toJson(Map.of(
                    "message", "scan " + scan.getPatientRef() + " -> " + scan.getResultat()
            ));
            HttpRequest req = HttpRequest.newBuilder()
                    .uri(URI.create(baseUrl + "/api/logs"))
                    .header("Content-Type", "application/json")
                    .POST(HttpRequest.BodyPublishers.ofString(body))
                    .build();
            HttpResponse<String> res = http.send(req, HttpResponse.BodyHandlers.ofString());
            System.out.println("[notifier] CI4 -> " + res.statusCode());
        } catch (Exception e) {
            System.err.println("[notifier] CI4 unreachable: " + e.getMessage());
        }
    }
}