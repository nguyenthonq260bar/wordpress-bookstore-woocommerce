#!/usr/bin/env python3
"""Extract sections from style.css (1-indexed ranges)."""
import os

SRC = os.path.join(os.path.dirname(__file__), '..', 'style.css')
DST = os.path.dirname(__file__)

with open(SRC, 'r') as f:
    lines = f.readlines()

def extract(start, end):
    """start/end are 1-indexed; end is exclusive."""
    chunk = lines[start-1:end-1]
    while chunk and chunk[0].strip() == '':
        chunk.pop(0)
    while chunk and chunk[-1].strip() == '':
        chunk.pop()
    chunk.append('\n')
    return chunk

def write(name, header, sections):
    """sections is list of (start_1indexed, end_1indexed)."""
    path = os.path.join(DST, name)
    with open(path, 'w') as f:
        f.write(header)
        for s, e in sections:
            for line in extract(s, e):
                f.write(line)
    # count lines
    with open(path) as f:
        cnt = len(f.readlines())
    print(f'  {name} ({cnt} lines)')

H_BASE    = '/* BASE - Fonts, Reset, Variables, Typography */\n\n'
H_LAYOUT  = '/* LAYOUT - Containers, Banner, Cards, Responsive */\n\n'
H_HEADER  = '/* HEADER - Nav, Toggle, Cart, Mobile */\n\n'
H_HERO    = '/* HERO - Hero Section */\n\n'
H_BUTTONS = '/* BUTTONS - btn, overlay-btn */\n\n'
H_FRONT   = '/* FRONT-PARTS - Cards, CTA, Newsletter, Footer */\n\n'
H_WC      = '/* WOOCOMMERCE - Shared */\n\n'
H_SP      = '/* WOO SINGLE PRODUCT */\n\n'
H_CART    = '/* WOO CART */\n\n'
H_CO      = '/* WOO CHECKOUT */\n\n'
H_ACCT    = '/* WOO ACCOUNT */\n\n'
H_BLOG    = '/* BLOG - Archive, Single, Search */\n\n'

write('base.css',       H_BASE,    [(12,266),   (650,920)])
write('layout.css',     H_LAYOUT,  [(920,1142), (3531,4248),
                                   (10125,10259), (10514,10622)])
write('header.css',     H_HEADER,  [(1142,1483), (330,345), (10259,10430),
                                    (10809,10824)])  # mode-toggle + register-btn responsive
write('hero.css',       H_HERO,    [(583,602),  (1483,1650)])
write('buttons.css',    H_BUTTONS, [(1650,1730), (2753,2828)])
write('front-parts.css',H_FRONT,   [(603,650),  (1730,2753), (2829,3530),
                                    (4248,4426)])
write('woocommerce.css',H_WC,      [(5276,5445), (9293,9384), (9955,9991),
                                    (10431,10513), (10741,10800),
                                    (10824,11010), (11281,11318)])
write('woo-single-product.css',H_SP, [(4426,5277), (9422,9649), (9991,10124)])
write('woo-cart.css',   H_CART,    [(346,453),  (5445,5772), (5772,6237),
                                    (6919,7230), (9384,9422), (10622,10656),
                                    (10801,10808)])  # cart toast responsive
write('woo-checkout.css',H_CO,     [(267,329),  (558,582),   (6237,6919),
                                    (7230,7661), (11011,11281)])
write('woo-account.css',H_ACCT,    [(454,558),  (7661,9278), (9279,9293),
                                    (9650,9955), (10656,10741)])
write('blog.css',       H_BLOG,    [(2488,2705)])

print('\nDone!')
