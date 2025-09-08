import React from "react";
import { createRoot } from "react-dom/client";

import ButtonModule from "@/modules/ButtonModule";

function PublicApp() {
  return (
    <>
      <ButtonModule
        title="Frontend — WPS Boiler Plate"
        subtitle="Rendered via [wps_boiler_plate] shortcode"
        ctaLabel="Buy Now"
        onCta={() => alert("Public CTA clicked")}
      />
    </>
  );
}

const el = document.getElementById("wpsbp-public-app");
if (el) createRoot(el).render(<PublicApp />);
