<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
  .wrapper { max-width: 580px; margin: 40px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
  .header { background: #1a1a2e; padding: 28px 32px; }
  .header h1 { color: #fff; font-size: 18px; margin: 0; }
  .body { padding: 32px; color: #333; line-height: 1.6; }
  .field { margin-bottom: 20px; }
  .field-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #888; margin-bottom: 4px; }
  .field-value { font-size: 15px; color: #1a1a2e; }
  .message-box { background: #f9f9f9; border-left: 3px solid #6366f1; padding: 16px 20px; border-radius: 0 6px 6px 0; white-space: pre-wrap; font-size: 15px; color: #333; }
  .footer { padding: 20px 32px; background: #f9f9f9; font-size: 12px; color: #aaa; border-top: 1px solid #eee; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <h1>Nouveau message — Portfolio</h1>
  </div>
  <div class="body">
    <div class="field">
      <div class="field-label">Nom</div>
      <div class="field-value">{{ $senderName }}</div>
    </div>
    <div class="field">
      <div class="field-label">Email</div>
      <div class="field-value"><a href="mailto:{{ $senderEmail }}" style="color:#6366f1;">{{ $senderEmail }}</a></div>
    </div>
    <div class="field">
      <div class="field-label">Message</div>
      <div class="message-box">{{ $messageBody }}</div>
    </div>
  </div>
  <div class="footer">
    Message reçu via le formulaire de contact de votre portfolio.
  </div>
</div>
</body>
</html>
