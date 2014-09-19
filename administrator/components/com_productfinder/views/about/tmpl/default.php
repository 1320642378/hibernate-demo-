<?php
/**
* Product Finder for Joomla! 2.5.x & 3.x
* @package Product Finder
* @subpackage Component
* @version 1.0
* @revision $Revision: 1.4 $
* @author Andrea Forghieri
* @copyright (C) 2012 - 2014 Andrea Forghieri, Solidsystem - http://www.solidsystem.it
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL version 2
*/
// no direct access
defined('_JEXEC') or die;

JToolBarHelper :: title(JText::_('COM_PRODUCTFINDER_LBL_ABOUT_PRODUCT_FINDER'), 'systeminfo.png');

//reading data from manifest
$data = JApplicationHelper::parseXMLInstallFile( JPATH_COMPONENT_ADMINISTRATOR . '/productfinder.xml');
$version = "1.1"; //default
$date = "2011-10-01";
if ( $data['type'] == 'component' )
{
	$version	= $data['version'];
	$date		= $data['creationDate'];
	$copy		= $data['copyright'];
}
?>
<table class="adminlist" style="width:80%; margin: 0 auto;" >
	<tr>
		<td style="width:256px">
			<img src="components/com_productfinder/assets/images/logo_productfinder.jpg" alt="Logo Product Finder"/>
		</td>
		<td>
			<h2>Product Finder for Joomla! 2.5.x and Joomla! 3.x</h2>
			<?php if(PFGetlevel()):?>
			<h3><?php echo PFGetVersion();?></h3>
			<?php endif;?>
			<p>Version <?php echo $version; ?>  (<?php echo $date;?>)</p>
			<p>
			<?php echo $this->escape($copy);?><br/>
			This component is released under the <a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_new">GNU/GPL version 2 License</a>.<br/>
			All copyright statements must be kept.
			</p>
			<p>Visit us: <a href="http://www.solidsystem.it">www.solidsystem.it</a></p>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="padding:1em">
				<strong>This software is provided "as is," without a warranty of any kind.</strong>
				All express or implied conditions, representations and warranties, including any
				implied warranty of merchantability, fitness for a particular purpose or
				non-infringement, are hereby excluded. Solidsystem shall
				not be liable for any damages suffered by licensee as a result of using,
				modifying or distributing the software or its derivatives. In no event will
				Solidsystem be liable for any lost revenue, profit or data,
				or for direct, indirect, special, consequential, incidental or punitive
				damages, however caused and regardless of the theory of liability, arising
				out of the use of or inability to use software, even if Solidsystem has been
				advised of the possibility of such damages.
		</td>
	</tr>
	<tr>
		<td colspan="2" style="padding:1em">
			<h3>Credits:</h3>
		</td>
	</tr>
	<tr>
		<td>Interface: jQuery Mobile 1.4.0</td>
		<td>http://jquerymobile.com</td>
	</tr>
	<tr>
		<td>Icons: Gentleface Toolbar Icon</td>
		<td>http://gentleface.com</td>
	</tr>
	
</table>