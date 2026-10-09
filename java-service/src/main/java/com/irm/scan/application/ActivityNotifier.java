package com.irm.scan.application;

import com.irm.scan.domain.model.Scan;

/**
 * Application port. Infrastructure implements it (HTTP -> CI4).
 */
public interface ActivityNotifier {
    void notifyCreated(Scan scan);
}