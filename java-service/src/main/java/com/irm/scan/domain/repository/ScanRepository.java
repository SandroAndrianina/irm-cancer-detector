package com.irm.scan.domain.repository;

import com.irm.scan.domain.model.Scan;
import java.util.List;

/**
 * Domain contract. Infrastructure decides HOW (JDBC, in-memory, ...).
 */
public interface ScanRepository {
    List<Scan> findAll();
    Scan save(Scan scan);
}