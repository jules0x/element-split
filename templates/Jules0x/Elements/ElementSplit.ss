<div class="element-split prefer__{$Prefer}"
    <% if $MinHeight > 0 %>style="min-height: {$MinHeight}px";<% end_if %>
     id="e{$ID}">
    <% if $IsLeftAligned %>
        <% include SplitImage %>
        <% include SplitContent %>
    <% else %>
        <% include SplitContent %>
        <% include SplitImage %>
    <% end_if %>
</div>
