
import * as React from "react";
import Typography from "@mui/material/Typography";
import WpsButton from "@/components/WpsButton";
import Grid from "@mui/material/Grid";
import Box from "@mui/material/Box";
import { Stack } from "@mui/system";
import Chip from "@mui/material/Chip";

export default function ButtonModule({
  title = "Simple Button",
  subtitle = "WpsButton.jsx",
  ctaLabel = "Click Me",
  onCta,
}) {
  const propsList = ["children", "variant", "size", "id", "classname"];

  return (
    <Grid container spacing={2} alignItems="start">
      <Grid item sx={{ maxWidth: "200px", width: "100%" }}>
        <Typography variant="h6" component="h2">
          {title}
        </Typography>
      </Grid>
      <Grid item container justifyContent="flex-end">
        <Stack spacing={1.5}>
          <Box>
            <WpsButton onClick={onCta}>{ctaLabel}</WpsButton>
          </Box>
          {subtitle && (
            <Typography variant="body2" component="p">
              {subtitle}
            </Typography>
          )}
          <Stack direction="row" spacing={1} flexWrap="wrap" alignItems="center">
            <Typography variant="body2">Props:</Typography>
            {propsList.map((prop, index) => (
              <Chip
                key={index}
                label={prop}
                variant="outlined"
                color="primary"
                size="medium"
              />
            ))}
          </Stack>
        </Stack>
      </Grid>
    </Grid>
  );
}
