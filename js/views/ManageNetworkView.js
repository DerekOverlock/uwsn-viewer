var app = app || {};

app.ManageNetworkView = Backbone.View.extend({
    initialize: function() {
        this.render();
    },
    events: {

    },
    render: function() {
        this.$el.append(
            Mustache.render(
                app.TemplateManager.getTemplate('manage_network'),
                {}
            )
        );
    }
});