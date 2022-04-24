<div role="menu" class="admintab-wrap menu-setting-wrap menu-setting-wrap-bg dropdown-menu animated zoomIn">
    <ul class="nav nav-tabs custon-set-tab">
        <li class="active"><a data-toggle="tab" href="#UsersConnected">Connecté(s)</a>
        </li>
        <li><a data-toggle="tab" href="#Projects">Activity</a>
        </li>
        <li><a data-toggle="tab" href="#Settings">Settings</a>
        </li>
    </ul>
    
    <div class="tab-content custom-bdr-nt">
        <div id="UsersConnected" class="tab-pane fade in active">
            <div class="notes-area-wrap">
                <div class="note-heading-indicate">
                    <h2><i class="fa fa-comments-o"></i> Utilisateur(s) Connecté(s)</h2>
                    <p>{!! $numberUserConnected () !!} Personne(s) en ligne.</p>
                </div>
                {!! $UsersConnected () !!}
            </div>
        </div>
        <div id="Projects" class="tab-pane fade">
            <div class="projects-settings-wrap">
                <div class="note-heading-indicate">
                    <h2><i class="fa fa-cube"></i> Recent Activity</h2>
                    <p> You have 20 Recent Activity.</p>
                </div>
                <div class="project-st-list-area project-st-menu-scrollbar">
                    <ul class="projects-st-menu-list">
                        <li>
                            <a href="#">
                                <div class="project-list-flow">
                                    <div class="projects-st-heading">
                                        <h2>New User Registered</h2>
                                        <p> The point of using Lorem Ipsum is that it has a more or less normal.</p>
                                        <span class="project-st-time">1 hours ago</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="project-list-flow">
                                    <div class="projects-st-heading">
                                        <h2>New Order Received</h2>
                                        <p> The point of using Lorem Ipsum is that it has a more or less normal.</p>
                                        <span class="project-st-time">2 hours ago</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="project-list-flow">
                                    <div class="projects-st-heading">
                                        <h2>New Order Received</h2>
                                        <p> The point of using Lorem Ipsum is that it has a more or less normal.</p>
                                        <span class="project-st-time">3 hours ago</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="project-list-flow">
                                    <div class="projects-st-heading">
                                        <h2>New Order Received</h2>
                                        <p> The point of using Lorem Ipsum is that it has a more or less normal.</p>
                                        <span class="project-st-time">4 hours ago</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="project-list-flow">
                                    <div class="projects-st-heading">
                                        <h2>New User Registered</h2>
                                        <p> The point of using Lorem Ipsum is that it has a more or less normal.</p>
                                        <span class="project-st-time">5 hours ago</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="project-list-flow">
                                    <div class="projects-st-heading">
                                        <h2>New Order</h2>
                                        <p> The point of using Lorem Ipsum is that it has a more or less normal.</p>
                                        <span class="project-st-time">6 hours ago</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="project-list-flow">
                                    <div class="projects-st-heading">
                                        <h2>New User</h2>
                                        <p> The point of using Lorem Ipsum is that it has a more or less normal.</p>
                                        <span class="project-st-time">7 hours ago</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="project-list-flow">
                                    <div class="projects-st-heading">
                                        <h2>New Order</h2>
                                        <p> The point of using Lorem Ipsum is that it has a more or less normal.</p>
                                        <span class="project-st-time">9 hours ago</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="Settings" class="tab-pane fade">
            <div class="setting-panel-area">
                <div class="note-heading-indicate">
                    <h2><i class="fa fa-gears"></i> Settings Panel</h2>
                    <p> You have 20 Settings. 5 not completed.</p>
                </div>
                <ul class="setting-panel-list">
                    <li>
                        <div class="checkbox-setting-pro">
                            <div class="checkbox-title-pro">
                                <h2>Show notifications</h2>
                                <div class="ts-custom-check">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example">
                                        <label class="onoffswitch-label" for="example">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="checkbox-setting-pro">
                            <div class="checkbox-title-pro">
                                <h2>Disable Chat</h2>
                                <div class="ts-custom-check">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example3">
                                        <label class="onoffswitch-label" for="example3">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="checkbox-setting-pro">
                            <div class="checkbox-title-pro">
                                <h2>Enable history</h2>
                                <div class="ts-custom-check">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example4">
                                        <label class="onoffswitch-label" for="example4">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="checkbox-setting-pro">
                            <div class="checkbox-title-pro">
                                <h2>Show charts</h2>
                                <div class="ts-custom-check">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example7">
                                        <label class="onoffswitch-label" for="example7">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="checkbox-setting-pro">
                            <div class="checkbox-title-pro">
                                <h2>Update everyday</h2>
                                <div class="ts-custom-check">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="collapsemenu" checked="" class="onoffswitch-checkbox" id="example2">
                                        <label class="onoffswitch-label" for="example2">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="checkbox-setting-pro">
                            <div class="checkbox-title-pro">
                                <h2>Global search</h2>
                                <div class="ts-custom-check">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="collapsemenu" checked="" class="onoffswitch-checkbox" id="example6">
                                        <label class="onoffswitch-label" for="example6">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="checkbox-setting-pro">
                            <div class="checkbox-title-pro">
                                <h2>Offline users</h2>
                                <div class="ts-custom-check">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="collapsemenu" checked="" class="onoffswitch-checkbox" id="example5">
                                        <label class="onoffswitch-label" for="example5">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            
            </div>
        </div>
    </div>
</div>
