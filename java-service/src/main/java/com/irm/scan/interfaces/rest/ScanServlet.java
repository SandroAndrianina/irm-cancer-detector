package com.irm.scan.interfaces.rest;

import com.irm.scan.application.ScanService;
import com.irm.scan.domain.model.Scan;
import com.irm.scan.domain.repository.ScanRepository;
import com.irm.scan.infrastructure.http.CiActivityNotifier;
import com.irm.scan.infrastructure.json.JsonMapper;
import com.irm.scan.infrastructure.persistence.ScanJdbcRepository;

import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.*;

import java.io.IOException;
import java.util.List;

/**
 * REST entrypoint. Only job: parse HTTP, call ScanService, write JSON.
 * Zero SQL, zero business rule here.
 */
@WebServlet(urlPatterns = "/api/scans")
public class ScanServlet extends HttpServlet {

    private ScanService service;

    @Override
    public void init() {
        ScanRepository repo = new ScanJdbcRepository();
        String phpBase = System.getenv("PHP_BASE_URL");
        service = new ScanService(repo, new CiActivityNotifier(phpBase));
    }

    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp) throws IOException {
        try {
            List<Scan> scans = service.listAll();
            writeJson(resp, 200, scans);
        } catch (Exception e) {
            writeError(resp, e);
        }
    }

    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws IOException {
        try {
            Scan in = JsonMapper.GSON.fromJson(req.getReader(), Scan.class);
            Scan saved = service.create(in);
            writeJson(resp, 201, saved);
        } catch (Exception e) {
            writeError(resp, e);
        }
    }

    private void writeJson(HttpServletResponse resp, int status, Object body) throws IOException {
        resp.setStatus(status);
        resp.setContentType("application/json; charset=UTF-8");
        resp.getWriter().write(JsonMapper.GSON.toJson(body));
    }

    private void writeError(HttpServletResponse resp, Exception e) throws IOException {
        resp.setStatus(500);
        resp.setContentType("application/json; charset=UTF-8");
        resp.getWriter().write(JsonMapper.GSON.toJson(
                java.util.Map.of("error", String.valueOf(e.getMessage()))));
    }
}