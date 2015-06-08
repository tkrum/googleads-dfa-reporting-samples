<?php
/*
 * Copyright 2015 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

// Require the base class.
require_once dirname(__DIR__) . "/BaseExample.php";

/**
 * This example shares an existing remarketing list with the specified
 * advertiser.
 *
 * Tags: remarketingListShares.update
 *
 * @author api.jimper@gmail.com (Jonathon Imperiosi)
 */
class ShareRemarketingList extends BaseExample {
  /**
   * (non-PHPdoc)
   * @see BaseExample::getInputParameters()
   * @return array
   */
  protected function getInputParameters() {
    return array(
        array('name' => 'user_profile_id',
              'display' => 'User Profile ID',
              'required' => true),
        array('name' => 'advertiser_id',
              'display' => 'Advertiser ID',
              'required' => true),
        array('name' => 'list_id',
              'display' => 'Remarketing List ID',
              'required' => true)
    );
  }

  /**
   * (non-PHPdoc)
   * @see BaseExample::run()
   */
  public function run() {
    $values = $this->formValues;

    printf(
        '<h2>Sharing remarketing list %s with advertiser ID %s</h2>',
        $values['list_id'], $values['advertiser_id']
    );

    // Load the existing share info.
    $share = $this->service->remarketingListShares->get(
        $values['user_profile_id'], $values['list_id']);

    $advertiser_ids = $share['sharedAdvertiserIds'];
    if(!isset($advertiser_ids)) {
      $advertiser_ids = array();
    }

    if(!in_array($values['advertiser_id'], $advertiser_ids)) {
      // Add the specified advertiser to the list of shared advertisers.
      $advertiser_ids[] = $values['advertiser_id'];
      $share->setSharedAdvertiserIds($advertiser_ids);

      // Update the share info with the newly added advertiser.
      $result = $this->service->remarketingListShares->update(
          $values['user_profile_id'], $share);

      $result['advertiserIds'] = implode(',', $result['sharedAdvertiserIds']);
      $this->printResultsTable('Remarketing list shared.', array($result));
    } else {
      print '<pre>Remarketing list is already shared with advertiser.</pre>';
    }
  }

  /**
   * (non-PHPdoc)
   * @see BaseExample::getName()
   * @return string
   */
  public function getName() {
    return 'Share Remarketing List With Advertiser';
  }

  /**
   * (non-PHPdoc)
   * @see BaseExample::getResultsTableHeaders()
   * @return array
   */
  public function getResultsTableHeaders() {
    return array(
        'remarketingListId' => 'Remarketing List ID',
        'advertiserIds' => 'Shared Advertiser IDs'
    );
  }
}
