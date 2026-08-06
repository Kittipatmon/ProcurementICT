---
name: Enterprise Procurement & ICT Operations Platform Design System
description: A clean, structured, corporate-grade design system focused on high legibility, professional Navy tones, and efficient enterprise workflows.
colors:
  primary: "#1e3a8a"
  accent-blue: "#3b82f6"
  neutral-bg: "#f8fafc"
  neutral-ink: "#0f172a"
  neutral-muted: "#64748b"
  border: "#cbd5e1"
  success: "#10b981"
  error: "#ef4444"
typography:
  display:
    fontFamily: "Instrument Sans, system-ui, sans-serif"
    fontSize: "clamp(2rem, 5vw, 3.5rem)"
    fontWeight: 700
    lineHeight: 1.1
    letterSpacing: "-0.02em"
  body:
    fontFamily: "Sarabun, system-ui, sans-serif"
    fontSize: "1rem"
    fontWeight: 400
    lineHeight: 1.6
rounded:
  sm: "4px"
  md: "8px"
  lg: "12px"
spacing:
  sm: "8px"
  md: "16px"
  lg: "24px"
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.neutral-bg}"
    rounded: "{rounded.md}"
    padding: "10px 20px"
  button-primary-hover:
    backgroundColor: "{colors.accent-blue}"
---

# Design System: Enterprise Procurement & ICT Operations Platform

## 1. Overview

**Creative North Star: "The Sentinel Ledger"**

The visual language of the platform is designed to convey absolute security, financial precision, and institutional trust. It is optimized for enterprise workflows where users manage budgets, procurement requests, and IT license allocations. Instead of trendy consumer-facing gradients and rounded shapes, this system uses sharp structures, high-density layouts, and high contrast to reduce cognitive load and facilitate rapid data entry.

Key characteristics:
*   **High-contrast corporate palette**: Classic Navy anchor tones with clear status identifiers.
*   **Rigid corner hierarchy**: A maximum border-radius of 12px enforces a formal and stable corporate environment.
*   **Structured typography**: Clear pairing of Instrument Sans for structural display elements and Sarabun for highly legible body copy.

## 2. Colors

The color palette centers on a deep, stable corporate Navy, utilizing light gray and silver tones for neutral backgrounds, and high-legibility alert colors for critical states.

### Primary
*   **Classic Navy** (#1e3a8a): The primary brand color. Used for headers, primary actions, and structural brand anchors.

### Secondary
*   **Slate Blue Accent** (#3b82f6): Used sparingly for interactive elements, highlights, and secondary active states.

### Neutral
*   **Slate Deep Ink** (#0f172a): Used for main body headings and highly legible body text.
*   **Slate Muted** (#64748b): Used for supporting text, labels, and less prominent information.
*   **Slate Light BG** (#f8fafc): The main page background color.
*   **Border Gray** (#cbd5e1): Standard border color for form elements, dividers, and cards.

### Named Rules
**The 10% Accent Rule.** The bright Slate Blue Accent must represent no more than 10% of any given viewport surface. Its purpose is to guide interaction, not to decorate.

**Detoxed Status Colors.** Status indicators (success, warning, error) must use muted, high-contrast values, not pure neon. 

## 3. Typography

**Display Font:** Instrument Sans (fallback: sans-serif)
**Body Font:** Sarabun (fallback: sans-serif)

The display font features a compact and structured geometric layout, while Sarabun provides exceptional legibility for both English and Thai characters at small sizes.

### Hierarchy
*   **Display** (700, clamp(2rem, 5vw, 3.5rem), 1.1): Used for main dashboard figures and major system headings.
*   **Headline** (700, 1.5rem, 1.2): Used for sections and page-level titles.
*   **Title** (600, 1.25rem, 1.3): Used for cards, tables, and modal headers.
*   **Body** (400, 1rem, 1.6): Used for normal descriptions, instructions, and standard copy.
*   **Label** (600, 0.75rem, 1.4): Used for form inputs, table headers, and status badges.

### Named Rules
**The Readability Cap.** Body columns containing prose or long instructions must be capped at 65–75 characters per line (approx. 500px to 600px width) to ensure reading ease.

## 4. Elevation

The system is flat-by-default to align with corporate simplicity and avoid distracting users from critical data values. 

### Shadow Vocabulary
*   **System Rest** (`box-shadow: none`): Default flat state with a clear border.
*   **Action Hover** (`box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05)`): A very subtle, small drop shadow used only when buttons or cards are hovered to indicate interactability.

### Named Rules
**The Flat-By-Default Rule.** Surfaces must remain flat with borders (`#cbd5e1`) to define boundaries. Shadows must never be used statically; they only appear during interactive states (hover/focus).

## 5. Components

### Buttons
*   **Shape:** Gently curved corners (8px radius, `rounded-md`).
*   **Primary:** Deep Classic Navy (`#1e3a8a`) background, white (`#f8fafc`) text.
*   **Hover / Focus:** Transitions to Slate Blue Accent (`#3b82f6`) with a smooth `150ms` transition.
*   **Secondary / Ghost:** Transparent background with Slate Ink border (`#cbd5e1`) and matching text.

### Chips
*   **Style:** Flat backgrounds, compact padding (4px 10px), uppercase font, rounded corners (4px, `rounded-sm`).
*   **State:** Neutral backgrounds for metadata, green background (`#e2fbf0` bg, `#10b981` text) for success, red background (`#fee2e2` bg, `#ef4444` text) for error.

### Cards / Containers
*   **Corner Style:** Structured corners (12px radius, `rounded-lg`).
*   **Background:** Pure white (`#ffffff`).
*   **Shadow Strategy:** Zero shadow at rest. Only thin gray borders (`1px solid #cbd5e1`).
*   **Internal Padding:** 24px (`lg`).

### Inputs / Fields
*   **Style:** Light background (`#f8fafc`), clean border (`#cbd5e1`), rounded corners (8px, `rounded-md`).
*   **Focus:** Border shifts to Accent Blue (`#3b82f6`) with a thin `1px` ring.
*   **Error / Disabled:** Disabled inputs become gray (`#e2e8f0`); error states receive a solid red border (`#ef4444`).

## 6. Do's and Don'ts

### Do:
*   **Do** enforce a strict contrast ratio of at least 4.5:1 on all text elements against their backgrounds.
*   **Do** keep border radii at or below 12px for all cards, inputs, and buttons.
*   **Do** use clean borders rather than large shadows to define container boundaries.

### Don't:
*   **Don't** use fancy gradients, text gradients, or complex background glows.
*   **Don't** use corner radii larger than 12px on any element.
*   **Don't** pair a border and a large, soft drop shadow together on the same card or button.
