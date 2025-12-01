---
name: interesting-fonts
description: Typography selection for distinctive frontend interfaces. Use when building web components, pages, or applications to select impactful, memorable fonts. Provides specific font recommendations, pairing strategies, and implementation patterns. Complements frontend-design skill.
---

# Interesting Fonts

Typography instantly signals quality. Never use boring, generic fonts.

## Banned Fonts

Never use: Inter, Roboto, Open Sans, Lato, Arial, Helvetica, or default system fonts.

## Recommended Fonts

| Aesthetic | Fonts |
|-----------|-------|
| Code/Technical | JetBrains Mono, Fira Code, Space Grotesk |
| Editorial/Classic | Playfair Display, Crimson Pro, Newsreader |
| Corporate/Clean | IBM Plex family, Source Sans 3 |
| Distinctive/Modern | Bricolage Grotesque, Syne, Cabinet Grotesk |

## Pairing Principle

High contrast = interesting. Effective pairings:
- Display + monospace
- Serif + geometric sans
- Variable font across extreme weights

## Weight & Size Extremes

Use extremes, not increments:
- **Weights**: 100/200 vs 800/900 (not 400 vs 600)
- **Sizes**: 3x+ jumps (not 1.5x)

## Implementation

1. Pick ONE distinctive font as the hero
2. Use it decisively throughout
3. Load from Google Fonts:

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=FONT_NAME:wght@100;900&display=swap" rel="stylesheet">
```

Replace `FONT_NAME` with chosen font (use `+` for spaces, e.g., `Playfair+Display`).
