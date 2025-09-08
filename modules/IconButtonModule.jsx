import * as React from "react";
import Typography from "@mui/material/Typography";
import WpsIconButton from "@/components/WpsIconButton";
import Grid from "@mui/material/Grid";
import { Stack } from "@mui/system";
import DeleteIcon from "@mui/icons-material/Delete";

export default function IconButtonModule({
  title = "Icon Button",
  subtitle = "WpsIconButton.jsx",
  ctaLabel = "Send again",
  onCta,
}) {
  return (
    <Grid container spacing={2} alignItems="start">
      <Grid item sx={{ maxWidth: "200px", width: "100%" }}>
        <Typography variant="h6" component="h2">
          {title}
        </Typography>
      </Grid>
      <Grid item container justifyContent="flex-end">
        <Stack spacing={1.5}>
          <WpsIconButton
            onClick={onCta}
            endIcon={<DeleteIcon className="BtnEndIcon" />}
            sx={{
              "& .BtnEndIcon": {
                marginLeft: 0,
              },
              borderRadius: "50px",
              padding: "14px",
              backgroundColor: "primary.purple",
              lineHeight: "1",
            }}
          >
            {ctaLabel}
          </WpsIconButton>
          {subtitle && (
            <Typography variant="body2" component="p">
              {subtitle}
            </Typography>
          )}
        </Stack>
      </Grid>
    </Grid>
  );
}
