package com.irm.scan.domain.model;

/**
 * Pure domain entity. No framework, no JDBC, no HTTP.
 * Carries the medical rule.
 */
public class Scan {

    private Integer id;
    private String patientRef;
    private String zone;            // brain, breast, liver, spine
    private boolean contrastUsed;   // MRI with contrast dye?
    private int signalIntensity;    // 0..100, from the IRM reading
    private String resultat;        // benin | malin | en_attente
    private String createdAt;

    public Scan() {}

    public Scan(Integer id, String patientRef, String zone,
                boolean contrastUsed, int signalIntensity,
                String resultat, String createdAt) {
        this.id = id;
        this.patientRef = patientRef;
        this.zone = zone;
        this.contrastUsed = contrastUsed;
        this.signalIntensity = signalIntensity;
        this.resultat = resultat;
        this.createdAt = createdAt;
    }

    /** The medical rule. Pure. Deterministic. Testable. */
    public String computeResultat() {
        if (contrastUsed && signalIntensity >= 70) return "malin";
        if (!contrastUsed || signalIntensity < 40)  return "benin";
        return "en_attente";
    }

    // getters / setters
    public Integer getId() { return id; }
    public void setId(Integer id) { this.id = id; }

    public String getPatientRef() { return patientRef; }
    public void setPatientRef(String patientRef) { this.patientRef = patientRef; }

    public String getZone() { return zone; }
    public void setZone(String zone) { this.zone = zone; }

    public boolean isContrastUsed() { return contrastUsed; }
    public void setContrastUsed(boolean contrastUsed) { this.contrastUsed = contrastUsed; }

    public int getSignalIntensity() { return signalIntensity; }
    public void setSignalIntensity(int signalIntensity) { this.signalIntensity = signalIntensity; }

    public String getResultat() { return resultat; }
    public void setResultat(String resultat) { this.resultat = resultat; }

    public String getCreatedAt() { return createdAt; }
    public void setCreatedAt(String createdAt) { this.createdAt = createdAt; }
}