package com.irm.scan.application;

import com.irm.scan.domain.model.Scan;
import com.irm.scan.domain.repository.ScanRepository;

import java.util.List;

/**
 * Application service. Orchestrates domain + ports. No SQL, no HTTP here.
 */
public class ScanService {

    private final ScanRepository repository;
    private final ActivityNotifier notifier;

    public ScanService(ScanRepository repository, ActivityNotifier notifier) {
        this.repository = repository;
        this.notifier = notifier;
    }

    public List<Scan> listAll() {
        return repository.findAll();
    }

    public Scan create(Scan scan) {
        scan.setResultat(scan.computeResultat());   // domain decides
        Scan saved = repository.save(scan);         // infra persists
        notifier.notifyCreated(saved);              // infra notifies CI4
        return saved;
    }
}