import * as React from "react";
import Button from "@mui/material/Button";

export default function WpsButton({
  id = "wps-btn",
  className = "wps-btn",
  variant = "contained",
  size = "small",
  children = "Click Me",
  ...rest
}) {
  return (
    <Button
      id={id}
      className={className}
      variant={variant}
      size={size}
      sx={{
        display: "inline-block",
        borderRadius: "50px",
        padding: "12px",
        backgroundColor: "primary.purple",
        lineHeight: "1",
      }}
      {...rest}
    >
      {children}
    </Button>
  );
}
