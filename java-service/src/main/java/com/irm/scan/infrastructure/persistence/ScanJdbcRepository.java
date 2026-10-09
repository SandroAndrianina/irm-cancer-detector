package com.irm.scan.infrastructure.persistence;

import com.irm.scan.domain.model.Scan;
import com.irm.scan.domain.repository.ScanRepository;

import java.sql.*;
import java.util.ArrayList;
import java.util.List;

/**
 * JDBC implementation of the domain repository.
 */
public class ScanJdbcRepository implements ScanRepository {

    @Override
    public List<Scan> findAll() {
        String sql = "SELECT id, patient_ref, zone, contrast_used, signal_intensity, resultat, created_at " +
                     "FROM scans ORDER BY id";
        List<Scan> out = new ArrayList<>();
        try (Connection c = DatabaseConnection.getConnection();
             PreparedStatement ps = c.prepareStatement(sql);
             ResultSet rs = ps.executeQuery()) {

            while (rs.next()) {
                out.add(map(rs));
            }
        } catch (SQLException e) {
            throw new RuntimeException("findAll failed: " + e.getMessage(), e);
        }
        return out;
    }

    @Override
    public Scan save(Scan s) {
        String sql = "INSERT INTO scans (patient_ref, zone, contrast_used, signal_intensity, resultat) " +
                     "VALUES (?, ?, ?, ?, ?)";
        try (Connection c = DatabaseConnection.getConnection();
             PreparedStatement ps = c.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS)) {

            ps.setString(1, s.getPatientRef());
            ps.setString(2, s.getZone());
            ps.setBoolean(3, s.isContrastUsed());
            ps.setInt(4, s.getSignalIntensity());
            ps.setString(5, s.getResultat());
            ps.executeUpdate();

            try (ResultSet keys = ps.getGeneratedKeys()) {
                if (keys.next()) s.setId(keys.getInt(1));
            }
            // reload to get created_at
            return findById(s.getId());
        } catch (SQLException e) {
            throw new RuntimeException("save failed: " + e.getMessage(), e);
        }
    }

    private Scan findById(int id) {
        String sql = "SELECT id, patient_ref, zone, contrast_used, signal_intensity, resultat, created_at " +
                     "FROM scans WHERE id = ?";
        try (Connection c = DatabaseConnection.getConnection();
             PreparedStatement ps = c.prepareStatement(sql)) {
            ps.setInt(1, id);
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) return map(rs);
            }
        } catch (SQLException e) {
            throw new RuntimeException("findById failed: " + e.getMessage(), e);
        }
        return null;
    }

    private Scan map(ResultSet rs) throws SQLException {
        return new Scan(
                rs.getInt("id"),
                rs.getString("patient_ref"),
                rs.getString("zone"),
                rs.getBoolean("contrast_used"),
                rs.getInt("signal_intensity"),
                rs.getString("resultat"),
                rs.getString("created_at")
        );
    }
}