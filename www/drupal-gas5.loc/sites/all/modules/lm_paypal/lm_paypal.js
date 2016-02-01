// $Id: lm_paypal.js,v 1.23 2006/10/08 20:51:08 leemcl Exp $
function lm_paypal_setbiz(form,user,host)
{
  form.business.value = user + '@' + host;
  return true;
}
